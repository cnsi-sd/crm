<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\MessageDocumentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\EmailNormalized;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Attachments\Model\Document;
use Exception;
use Illuminate\Support\Str;
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
            'password' => env('MANOMANO_PASSWORD'),
            'client' => env('MANOMANO_CLIENT')
        ];
    }

    /**
     * @param EmailNormalized $email
     * @return bool
     * @throws Exception
     */
    protected function canImport(EmailNormalized $email): bool
    {
        // Check if sender is "ne-pas-repondre@manomano.fr"
        if(str_contains($email->getSender(), 'repondre'))
            return false;

        // Check if there is an OrderId
        if(!$this->parseOrderId($email))
            return false;

        return parent::canImport($email);
    }

    /**
     * @param EmailNormalized $email
     * @return bool|string
     */
    protected function parseOrderId(EmailNormalized $email): bool|string
    {
        $subject = $email->getSubject();

        // get the orderId (Mxxxxxxxxxxxx pattern) in subject
        preg_match('/M(\d{12})/',$subject, $orderMatche);

        if(isset($orderMatche[0]))
            return $orderMatche[0];
        return false;
    }

    /**
     * @param EmailNormalized $email
     * @param string $mpOrder
     * @throws Exception
     */
    protected function importEmail(EmailNormalized $email, string $mpOrder): void
    {
        $threadName = str_contains($email->getSender(), '@monechelle.zendesk.com') ? 'Support' : 'Client';
        $threadNumber = Str::before($email->getSender(), '@');
        $channel_data = ["email" => $email->getSender()];
        $order      = Order::getOrder($mpOrder, $this->channel);
        $ticket     = Ticket::getTicket($order, $this->channel);
        $thread     = Thread::getOrCreateThread($ticket, $threadNumber, $threadName, $channel_data);

        $this->importMessageByThread($ticket, $thread, $email);
    }

    /**
     * @param EmailNormalized $email
     * @return string
     */
    public function getMessageContent(EmailNormalized $email): string
    {
        $subject = $email->getSubject();
        if(empty($email->getTextPlain())) {
            $content = strip_tags($email->getContent()); // remove html
            $content = preg_replace('/\s+/', ' ', $content); // remove whitespaces
            $content = preg_replace('/@(.*); }/', '',$content); // remove css
        }
        else {
            $content = preg_replace('/(\v+)/', PHP_EOL, $email->getTextPlain());
        }

        if(str_contains($subject, 'Demande de facture'))
            $content = 'Pouvez-vous répondre à cet email avec la facture au format pdf en pièce-jointe svp?';

        return $content;
    }

    /**
     * @param Ticket $ticket
     * @param $message_api_api
     * @param Thread $thread
     * @param array $attachments
     * @return mixed
     */
    public function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread, $attachments = []): mixed
    {
        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::OPENED;
        $ticket->save();
        $this->logger->info('Ticket save');

        if(str_contains($message_api_api->getSender(),'support'))
            $authorType = TicketMessageAuthorTypeEnum::OPERATOR;
        else
            $authorType = TicketMessageAuthorTypeEnum::CUSTOMER;

        $message = Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message_api_api->getEmailId(),
        ],
            [
                'user_id' => null,
                'author_type' => $authorType,
                'content' => $this->getMessageContent($message_api_api),
            ]
        );

        if ($message_api_api->hasAttachments()) {
            $this->logger->info('Download documents from message');
            foreach ($message_api_api->getAttachments() as $attachment) {
                Document::doUpload($attachment->getTmpFile(), $message, MessageDocumentTypeEnum::OTHER, null, $attachment->getName());
            }
        }

        $this->logger->info('Message id: '. $message->id . ' created');

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }
}
