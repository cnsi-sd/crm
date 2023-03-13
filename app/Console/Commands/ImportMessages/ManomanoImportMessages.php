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

class ManomanoImportMessages extends AbstractImportMessages
{
    /** @var Mailbox */
    private Mailbox $mailbox;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';

    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'manomano');
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->channel = Channel::getByName(ChannelEnum::MANOMANO_COM);
        $this->logger = new Logger('import_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        try {
            DB::beginTransaction();
            $from_time = strtotime(date('d M Y H:i:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date("d M Y H:i:s", $from_time);

            $this->logger->info('--- Init api client ---');

            $this->initApiClient();

            $this->logger->info('--- Init filters ---');
            $emailIds = $this->search([
                'SINCE' => $from_date
            ]);

            $this->logger->info('Get Emails details');

            foreach($this->getEmails($emailIds) as $emailId => $email){
                // Check if sender is "ne-pas-repondre@manomano.fr"
                $doNotReply = str_contains($email->senderAddress, 'repondre');
                if($doNotReply)
                    continue;

                $orderId = $this->parseOrderId($email);
                if(!$orderId)
                    continue;

                if(str_contains($email->senderAddress, '@monechelle.zendesk.com'))
                    $threadPrefix = 'Support';
                else
                    $threadPrefix = 'Client';

                $order      = Order::getOrder($orderId, $this->channel);
                $ticket     = Ticket::getTicket($order, $this->channel);
                $thread     = Thread::getOrCreateThread($ticket, $threadPrefix.'-'.$orderId, $threadPrefix.'-'.$email->subject, $email->senderAddress);

                if(!$this->isMessagesImported($email->messageId)) {
                    $this->logger->info('Convert api message to db message');
                    $this->convertApiResponseToMessage($ticket, $email, $thread);
                    $this->addImportedMessageChannelNumber($email->messageId);
                }
                DB::commit();
            }
        } catch (Exception $e){
            $this->logger->error('An error has occurred. Rolling back.', $e);
            DB::rollBack();
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }
    protected function getCredentials(): array
    {
        return [
            'host' => env('MANOMANO_MAIL_URL'),
            'login' => env('MANOMANO_USERNAME'),
            'password' => env('MANOMANO_PASSWORD')
        ];
    }

    /**
     * @throws InvalidParameterException
     */
    protected function initApiClient()
    {
        $credentials = $this->getCredentials();
        $this->mailbox = new Mailbox(
            '{'. $credentials['host'].':993/imap/ssl/novalidate-cert}INBOX',
            $credentials['login'],
            $credentials['password']
        );
    }

    private function search($query = []): array
    {
        if(empty($query)) {
            $query = ['All' => null];
        }

        $criterias = [];
        foreach($query as $criteria => $value) {
            if(empty($value)) {
                $criterias[] = strtoupper($criteria);
                continue;
            }
            $criterias[] = strtoupper($criteria).' "'.$value.'"';
        }

        return $this->mailbox->searchMailbox(implode(' ', $criterias));
    }

    private function getEmails($emailIds): array
    {
        $emails = [];
        foreach ($emailIds as $emailId) {
            $this->logger->info('Get Email : '. $emailId);
            $emails[$emailId] = $this->mailbox->getMail($emailId,false);
        }
        return $emails;
    }

    private function parseOrderId(IncomingMail $email): bool|string
    {
        $subject = $email->subject;

        // get the orderId (Mxxxxxxxxxxxx pattern) in subject
        preg_match('/M(\d{12})/',$subject, $orderMatche);

        if(isset($orderMatche[1]))
            return 'M'. $orderMatche[1];

        $this->logger->info('No Order found in '. $subject);
        return false;
    }

    public function getMessageContent(IncomingMail $email): string
    {
        $subject = $email->subject;
        $attachment = $email->getAttachments();

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
    public function convertApiResponseToMessage(Ticket $ticket, $email, Thread $thread)
    {
        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::WAITING_ADMIN;
        $ticket->save();
        $this->logger->info('Ticket save');

        $support = str_contains($email->senderAddress,'support');
        if($support)
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

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }
}
