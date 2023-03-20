<?php

namespace App\Console\Commands\ImportMessages;

use App\Console\Commands\ImportMessages\Beautifier\AmazonBeautifierMail;
use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketCommentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Tools;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Tags\Tag;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpImap\Exceptions\InvalidParameterException;
use PhpImap\Mailbox;

class AmazonImportMessage extends AbstractImportMailMessages
{
    /** @var Mailbox */
    private Mailbox $mailbox;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';
    const RETURN = 'retour';
    const IMPORT = 'import';
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'amazon');
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle(){
        $this->channel = Channel::getByName(ChannelEnum::AMAZON_FR);
        $this->logger = new Logger('import_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');
        try {
            $from_time = strtotime(date('d M Y H:i:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date("d M Y H:i:s", $from_time);

            $this->logger->info('--- Init api client ---');

            $this->initApiClient();

            $this->logger->info('--- Init filters ---');
            $emailIds = $this->search([
                //'SINCE' => $from_date
            ]);

            $this->logger->info('--- Get Emails details');
            foreach(array_reverse($this->getEmails($emailIds)) as $emailId => $email) {
                try {
                    // Check can import mail
                    if(!$this->canImport($email))
                        continue;

                    $this->logger->info('Retrieve command number from email');
                    $mpOrder = $this->parseOrderId($email);

                    if (!$mpOrder)
                        continue;

                    $this->logger->info('Begin Transaction');
                    DB::beginTransaction();
                    $this->logger->info('--- start import email : ' . $email->id);
                    $order = Order::getOrder($mpOrder, $this->channel);
                    $ticket = Ticket::getTicket($order, $this->channel);
                    $thread = Thread::getOrCreateThread($ticket, $mpOrder, $email->subject, $email->fromAddress);

                    switch ($this->getSpecificActions($email)) {
                        case self::RETURN :
                            $this->addReturnOnTicket($ticket, $email);
                            break;
                        default:
                            $this->importMessageByThread($ticket, $thread, $email);
                        }
                    $this->logger->info('--- end import email');
                    DB::commit();
                } catch (Exception $e) {
                    $this->logger->error('An error has occurred. Rolling back.', $e);
                    DB::rollBack();
                    \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                    return;
                }
            }
        } catch (Exception $e){
            $this->logger->error('An error has occurred. Rolling back.', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
    }
    protected function getCredentials(): array
    {
        return [
            'API_URL'            => env('AMAZON_MAIL_URL'),
            'API_USERNAME'       => env('AMAZON_USERNAME'),
            'API_PASSWORD'       => env('AMAZON_PASSWORD')
        ];
    }

    /**
     * @throws InvalidParameterException
     */
    protected function initApiClient()
    {
        $credentials = $this->getCredentials();
        $this->mailbox = new Mailbox(
            '{'. $credentials['API_URL'].':993/imap/ssl/novalidate-cert}INBOX',
            $credentials['API_USERNAME'],
            $credentials['API_PASSWORD']
        );
    }

    /**
     * @param $email
     * @return bool
     */
    public function canImport($email): bool
    {
        /*
         * No authorized subjects
         */
        $patterns = [
            '#remboursementinitieacutepourlacommande#',
            '#actionrequise#',
            '#amazonfruneouplusieursdevosoffresamazononteacuteteacutesupprimeacuteesdelarecherche#',
            '#offredeacutesactiveacuteesenraisonduneerreurdeprixpotentielle#',
            '#votreemaila#'
        ];
        $normalizedSubject = Tools::normalize($email->subject);
        foreach ($patterns as $pattern)
            if (preg_match($pattern, $normalizedSubject))
                return false;

        return parent::canImport($email);
    }

    /**
     * @param $email
     * @return bool|string
     */
    public function parseOrderId($email): bool|string
    {
        $pattern = '#(?<orderId>\d{3}-\d{7}-\d{7})#';
        preg_match($pattern, $email->subject, $orderId);
        if (isset($orderId['orderId'])) {
            $this->logger->info('Amazon : orderId found from Subject '.$orderId['orderId']);
            return $orderId['orderId'];
        }

        preg_match($pattern, $email->textHtml, $orderId);
        if (isset($orderId['orderId'])) {
            $this->logger->info('Amazon : orderId found from Body '.$orderId['orderId']);
            return $orderId['orderId'];
        }

        return false;
    }

    /**
     * @param $email
     * @return string
     */
    protected function getSpecificActions($email): string
    {
        $normalizedSubject = Tools::normalize($email->subject);
        if (str_contains($normalizedSubject, 'autorisationderetourpourlacommande'))
            return self::RETURN;

        return self::IMPORT;
    }

    /**
     * @param Ticket $ticket
     * @param Thread $thread
     * @param $message
     * @return void
     * @throws Exception
     */
    private function importMessageByThread(Ticket $ticket, Thread $thread, $message): void
    {
        $imported_id = $message->id;
        $this->logger->info('Check if this message is imported');
        if (!$this->isMessagesImported($imported_id)) {
            $this->logger->info('Convert api message to db message');
            $this->convertApiResponseToMessage($ticket, $message, $thread);
            $this->addImportedMessageChannelNumber($imported_id);
        }
    }

    /**
     * @param Ticket $ticket
     * @param $message_api_api
     * @param Thread $thread
     * @return void
     */
    protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread): void
    {
        $this->logger->info('Retrieve message from email');
        $infoMail = $message_api_api->textHtml;
        $message = AmazonBeautifierMail::getCustomerMessage($infoMail);

        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::WAITING_ADMIN;
        $ticket->save();
        $this->logger->info('Ticket save');
        $message = Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message_api_api->messageId,
        ],
            [
                'user_id' => null,
                'author_type' => TicketMessageAuthorTypeEnum::CUSTOMER,
                'content' => strip_tags($message),
            ],
        );

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }

    /**
     * @param Ticket $ticket
     * @param mixed $email
     * @return void
     */
    private function addReturnOnTicket(Ticket $ticket, mixed $email): void
    {
        $tagId = setting('tag.retour_amazon');
        $tag = Tag::findOrFail($tagId);
        $ticket->addTag($tag);

        $infoMail = $email->textHtml;
        $returnComment = AmazonBeautifierMail::getReturnInformation($infoMail);
        if ($returnComment !== "") {
            $comment = new Comment();
            $comment->ticket_id = $ticket->id;
            $comment->content = $returnComment;
            $comment->displayed = 1;
            $comment->type = TicketCommentTypeEnum::INFO_IMPORTANT;
            $comment->save();
        }
    }

}
