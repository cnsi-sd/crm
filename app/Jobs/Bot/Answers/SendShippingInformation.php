<?php

namespace App\Jobs\Bot\Answers;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Ticket\Message;
use Illuminate\Support\Str;

class SendShippingInformation extends AbstractAnswer
{
    public function handle(): bool
    {
        if (!$this->canBeProcessed())
            return self::SKIP;

        $prestashopOrder = $this->message->thread->ticket->order->getFirstPrestashopOrder();

        if ($prestashopOrder['is_fulfillment']) {
            $this->sendAnswer(setting('bot.shipping_information.fulfillment_answer_id'));
            $this->message->thread->ticket->close();
            return self::STOP_PROPAGATION;
        }

        if ($this->isStateInPreparation($prestashopOrder)) {
            if ($this->isOrderWithDelay($prestashopOrder)) {
                $this->sendAnswer(setting('bot.shipping_information.in_preparation_with_delay_answer_id'));
            } else {
                $this->sendAnswer(setting('bot.shipping_information.in_preparation_answer_id'));
            }

            // If the max_ship_date is not reached, close ticket
            if(Order::getOrderDelay($prestashopOrder) !== 0) {
                $this->message->thread->ticket->close();
            }
            // Otherwise (max_ship_date reached), add tag on ticket, stay open
            else {
                $tagId = setting('bot.shipping_information.late_order_tag_id');
                $tag = Tag::findOrFail($tagId);
                $this->message->thread->ticket->addTag($tag);
            }

            return self::STOP_PROPAGATION;
        }

        $order_nb_days = Order::getOrderCreatedSinceNbDays($prestashopOrder);
        if ($this->isStateShipped($prestashopOrder) && $order_nb_days < 3) {
            if ($this->isVirSupplier($prestashopOrder)) {
                $this->sendAnswer(setting('bot.shipping_information.vir_shipped_answer_id'));
            } else {
                $this->sendAnswer(setting('bot.shipping_information.default_shipped_answer_id'));
            }

            $this->message->thread->ticket->close();
            return self::STOP_PROPAGATION;
        }

        return self::SKIP;
    }

    protected function canBeProcessed(): bool
    {
        if (!setting('bot.shipping_information.active'))
            return false;

        if (!$this->message->isExternal())
            return false;

        if (!$this->message->isFirstMessageOnThread())
            return false;

        if($this->message->hasBeenAnswered())
            return false;

        if (!$this->matchPatterns($this->message))
            return false;

        return true;
    }

    private function matchPatterns(Message $message): bool
    {
        $patterns = [
            "(Information sur la livraison|Article non reçu)", // Mirakl
            "(Je n'ai pas reçu mon colis|Ma commande est expédiée, mais je ne l'ai pas reçue)", // Cdiscount
            "(Avez-vous expédié l'article ?)", // Rakuten
            "(Demande de renseignements concernant la livraison d'une commande)", // Amazon
        ];

        $threadSubject = $message->thread->name;
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $threadSubject, $matches)) {
                return true;
            }
        }

        return false;
    }

    private function isStateInPreparation($prestashopOrder): bool
    {
        return Str::contains($prestashopOrder['state']['name'], [
            'paiement accepté',
            'en cours de préparation',
            'en cours de conformité',
            'en cours d\'envoi',
            'expédié de entrepôts cnsi',
        ], true);
    }

    private function isStateShipped($prestashopOrder): bool
    {
        return Str::contains($prestashopOrder['state']['name'], [
            'expédié',
            'expédié sans maj mp',
            'a expédier manuellement',
        ], true);
    }

    protected function isVirSupplier($prestashopOrder): bool
    {
        return Str::contains($prestashopOrder['shipping']['carrier'], 'VIR', true);
    }

    private function isOrderWithDelay($prestashopOrder): bool
    {
        $orderDelay = Order::getOrderDelay($prestashopOrder);
        return $orderDelay >= 10;
    }

    private function sendAnswer(int $defaultAnswerId)
    {
        $defaultAnswer = DefaultAnswer::findOrFail($defaultAnswerId);

        // Build message
        $answer = new Message();
        $answer->thread_id = $this->message->thread_id;
        $answer->user_id = null;
        $answer->channel_message_number = '';
        $answer->author_type = TicketMessageAuthorTypeEnum::SYSTEM;
        $answer->content = $defaultAnswer->content;
        $answer->save();

        // Send message
        AbstractSendMessage::dispatchMessage($answer);
    }
}
