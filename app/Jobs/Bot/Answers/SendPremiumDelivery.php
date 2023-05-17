<?php

namespace App\Jobs\Bot\Answers;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Helpers\Prestashop\CrmLinkGateway;
use App\Helpers\Tools;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;

class SendPremiumDelivery extends AbstractAnswer
{
    private CrmLinkGateway $prestashopGateway;

    public function handle(): bool
    {
        if (!$this->canBeProcessed())
            return self::SKIP;

        $prestashopOrder = $this->message->thread->ticket->order->getFirstPrestashopOrder();

        if (in_array($prestashopOrder['delivery_shipping_mode'], ['DEBALLE', 'INSTALLE'])) {
            $this->premiumDeliveryAutoReply($this->message->thread);
        }
    }

    protected function canBeProcessed(): bool
    {
        if (!setting('bot.premium_delivery.active'))
            return false;

        if (!$this->message->isExternal())
            return false;

        if (!$this->message->isFirstMessageOnThread())
            return false;

        if ($this->message->hasBeenAnswered())
            return false;

        if (!$this->matchPatterns($this->message))
            return false;

        return true;
    }

    private function matchPatterns(Message $message): bool
    {
        $patterns = [
            '(installation)',
            '(livraison étage)',
            '(pied du camion)',
            '(escalier)',
            '(déballage)',
            '(reprise eco taxe)',
            '(reprise ancien produit)'
        ];

        $threadSubject = $message->thread->name;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $threadSubject, $matches)) {
                return true;
            }
        }

        return false;
    }

    protected function premiumDeliveryAutoReply(Thread $thread): void
    {
        $defaultAnswer = DefaultAnswer::findOrFail(setting('bot.premium_delivery.premium_reply'));
        $messageQuerycount = Message::query()->where("thread_id", $thread->id)->where("content", $defaultAnswer->content)->count();
        if ($messageQuerycount == 0) {
            Message::firstOrCreate([
                    'thread_id' => $thread->id,
                    'content' => strip_tags($defaultAnswer->content)
                ],
                [
                    'channel_message_number' => null,
                    'user_id' => null,
                    'author_type' => TicketMessageAuthorTypeEnum::SYSTEM,
                ]
            );
        }
    }
}
