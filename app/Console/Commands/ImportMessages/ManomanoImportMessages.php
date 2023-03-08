<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\Mailbox;

class ManomanoImportMessages extends AbstractImportMessages
{
    /** @var Mailbox */
    private Mailbox $mailbox;
    const FROM_DATE_TRANSFORMATOR = ' - 24 hours';

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
                $sender = $email->senderAddress;

                // Check if sender is "ne-pas-repondre@manomano.fr"
                $doNotReply = strpos($sender, 'repondre');
                if($doNotReply)
                    continue;

                $message = $this->messageProcess($email);

                $support = strpos($sender,'support');
                if($support)
                    $message['authorType'] = TicketMessageAuthorTypeEnum::OPERATOR;
                else
                    $message['authorType'] = TicketMessageAuthorTypeEnum::CUSTOMER;

                $message['date']    = $from_time;
                $message['subject'] = $email->subject;
                $message['id']      = $email->messageId;


                $order      = Order::getOrder($message['orderId'], $this->channel);
                $ticket     = Ticket::getTicket($order, $this->channel);
                $thread     = Thread::getOrCreateThread($ticket, $message['orderId'], $email->subject, '');

                if(!$this->isMessagesImported($email->messageId)) {
                    $this->logger->info('Convert api message to db message');
                    $this->convertApiResponseToMessage($ticket, $message, $thread);
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

    private function parseOrderId($subject): bool|string
    {
        // get the orderId (Mxxxxxxxxxxxx pattern) in subjet
        preg_match('/M(\d{12})/',$subject, $orderMatche);

        if(isset($orderMatche[1])){
            return $orderMatche[1];
        }
        $this->logger->info('No OrderId found in '. $subject);
        return false;
    }

    public function messageProcess($email): array
    {
        $message = [];

        $plainBody = $email->textPlain;
        $subject = $email->subject;
        $attachment = $email->getAttachments();

        // html process to be readable
        $withoutHtml = strip_tags($email->textHtml);
        $withoutSpaces = preg_replace('/\s+/', ' ', $withoutHtml);
        $withoutCss = preg_replace('/@(.*); }/', '',$withoutSpaces);

        if(strlen($email->textPlain) > 0)
            $message['content'] = preg_replace('/(\v+)/', PHP_EOL, $plainBody);
        else
            $message['content'] = $withoutCss;

        if(strpos($subject, 'facture'))
            $message['content'] = 'Pouvez-vous répondre à cet email avec la facture au format pdf en pièce-jointe svp?';

        $this->parseOrderId($subject)
            ? $message['orderId'] = $this->parseOrderId($subject)
            : $message['orderId'] = 'No_order_found';

        return $message;
    }

    /**
     * @throws Exception
     */
    public function convertApiResponseToMessage(Ticket $ticket, $message, Thread $thread)
    {
        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::WAITING_ADMIN;
        $ticket->save();
        $this->logger->info('Ticket save');

        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::WAITING_ADMIN;
        $ticket->save();
        $this->logger->info('Ticket save');

        Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message['id'],
        ],
            [
                'user_id' => null,
                'author_type' => $message['authorType'],
                'content' => $message['content'],
            ]
        );

//        self::sendAutoReply($thread);
    }
}
