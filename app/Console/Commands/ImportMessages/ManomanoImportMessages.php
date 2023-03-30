<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\IncomingMail;
use PhpImap\Mailbox;

class ManomanoImportMessages extends AbstractImportMailMessages
{
    /** @var Mailbox */
    protected Mailbox $mailbox;

    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'manomano');
        $this->channelName = ChannelEnum::MANOMANO_COM;
        $this->reverse = false;
        parent::__construct();
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'host' => env('MANOMANO_MAIL_URL'),
            'username' => env('MANOMANO_USERNAME'),
            'password' => env('MANOMANO_PASSWORD')
        ];
    }

    protected function canImport($email): bool
    {
        // Check if sender is "ne-pas-repondre@manomano.fr"
        if(str_contains($email->senderAddress, 'repondre'))
            return false;

        // Check if there is an OrderId
        if(!$this->parseOrderId($email))
            return false;

        return parent::canImport($email);
    }

    protected function parseOrderId($email): bool|string
    {
        $subject = $email->subject;

        // get the orderId (Mxxxxxxxxxxxx pattern) in subject
        preg_match('/M(\d{12})/',$subject, $orderMatche);

        if(isset($orderMatche[0]))
            return $orderMatche[0];
        return false;
    }

    /**
     * @param IncomingMail $email
     * @param string $mpOrder
     * @throws Exception
     */
    protected function importEmail(IncomingMail $email, string $mpOrder): void
    {
        if(str_contains($email->senderAddress, '@monechelle.zendesk.com'))
            $threadPrefix = 'Support';
        else
            $threadPrefix = 'Client';

        $order      = Order::getOrder($mpOrder, $this->channel);
        $ticket     = Ticket::getTicket($order, $this->channel);
        $thread     = Thread::getOrCreateThread($ticket, $threadPrefix.'-'.$mpOrder, $threadPrefix, $email->senderAddress);

        $this->importMessageByThread($ticket, $thread, $email);
    }

    public function getMessageContent(IncomingMail $email): string
    {
        $subject = $email->subject;
        if(empty($email->textPlain)) {
            $content = strip_tags($email->textHtml); // remove html
            $content = preg_replace('/\s+/', ' ', $content); // remove whitespaces
            $content = preg_replace('/@(.*); }/', '',$content); // remove css
        }
        else {
            $content = preg_replace('/(\v+)/', PHP_EOL, $email->textPlain);
        }

        if(str_contains($subject, 'Demande de facture'))
            $content = 'Pouvez-vous répondre à cet email avec la facture au format pdf en pièce-jointe svp?';

        return $content;
    }

    /**
     * @throws Exception
     */
    public function convertApiResponseToMessage(Ticket $ticket, $email, Thread $thread, $attachments = [])
    {
        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::WAITING_ADMIN;
        $ticket->save();
        $this->logger->info('Ticket save');

        if(str_contains($email->senderAddress,'support'))
            $authorType = TicketMessageAuthorTypeEnum::OPERATOR;
        else
            $authorType = TicketMessageAuthorTypeEnum::CUSTOMER;

        $message = Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $email->messageId,
        ],
            [
                'user_id' => null,
                'author_type' => $authorType,
                'content' => $this->getMessageContent($email),
            ]
        );

        $this->logger->info('Message id: '. $message->id . ' created');

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }
}
