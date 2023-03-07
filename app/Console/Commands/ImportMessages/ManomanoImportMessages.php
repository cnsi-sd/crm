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
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\Mailbox;

class ManomanoImportMessages extends AbstractImportMessages
{
    /** @var Mailbox */
    private Mailbox $mailbox;
    const FROM_DATE_TRANSFORMATOR = ' - 1 hours';

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
            $from_time = strtotime(date('d M Y H:i:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date("d M Y H:i:s", $from_time);

            $this->logger->info('--- Init api client ---');

            $this->initApiClient();

            $this->logger->info('--- Init filters ---');
            $emailIds = $this->search([
//                'SUBJECT' => 'Demande de renseignements',
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

                $this->convertApiResponseToMessage($ticket, $message, $thread);
                $test = '';
            }
        } catch (Exception $e){
            $this->logger->error('An error has occurred. Rolling back.', $e);
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

    private function parseHtmlMessage($htmlEmail)
    {
        $pattern = "/Message:(.*)<br/";
        preg_match($pattern,$htmlEmail,$matches);
        return $matches;
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
        /**
         * on vérif le sender; si contient "repondre" on tèj, s'il contient "support" à voir le traitement mais c'est pas clair ( autant le traiter comme les autres )
         * on vérif le subject => on prend le numéro de commande M(\d+12) ( qui sera le numéro de thread et l'orderId; le default "manomano_support"  )
         * Si c'est [
         *  une demande de facture,
         *  demande d'information
         *  ]
         *  => on remplie le sujet, thread number, order, mais pour pour le content, on écrit un message bateau.
         *
         * on vérif s'il existe le textPlain, sinon on prend le textHtml.
         */

        $isReply = $email->replyTo;
        $body = $email->textHtml;
        $plainBody = $email->textPlain;
        $subject = $email->subject;
        $attachment = $email->getAttachments();
        $messageId = $email->messageId; // todo parse id <[idToParse]@swift.generated>
//        $parsedMessage = $this->parseHtmlMessage($email->textHtml);



        if(strlen($email->textPlain))
            $message['content'] = $email->textPlain;
        else
            // TODO delete all space and CSS
            $message['content'] = strip_tags($email->textHtml);

        if(strpos($subject, 'facture'))
            $message['content'] = 'Pouvez-vous répondre à cet email avec la facture au format pdf en pièce-jointe svp?';

        $message['orderId'] = $this->parseOrderId($subject);

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
                'content' => strip_tags($message['content'])
            ]
        );

//        self::sendAutoReply($thread);
    }
}
