<?php

namespace App\Console\Commands\ImportMessages;

use App\Console\Commands\ImportMessages\Beautifier\AmazonBeautifierMail;
use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\Stringer;
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
use PhpImap\Mailbox;

class AmazonImportMessage extends AbstractImportMessages
{
    /** @var Mailbox */
    private Mailbox $mailbox;
    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'amazon');
        //$this->FROM_SHOP_TYPE = 'Seller';
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
                'SUBJECT' => 'Demande de',
                'SINCE' => $from_date
            ]);

            $this->logger->info('--- Get Emails details');
            foreach(array_reverse($this->getEmails($emailIds)) as $emailId => $email) {
                try {
                    $t = $email;
                    $fd= 'df';
                    DB::beginTransaction();
                    $this->logger->info('Begin Transaction');

                    $this->logger->info('Retrieve command number from email');

                    $patterns = array();
                    $patterns[] = array('pattern' => '#remboursementinitieacutepourlacommande#'); //Remboursement initié pour la commande <num_cmd>
                    $patterns[] = array('pattern' => '#actionrequise#'); //Action requise: ...
                    $patterns[] = array('pattern' => '#amazonfruneouplusieursdevosoffresamazononteacuteteacutesupprimeacuteesdelarecherche#'); // [Amazon.fr] Une ou plusieurs de vos offres Amazon ont été supprimées de la recherche
                    $patterns[] = array('pattern' => '#demandedrsquoautorisationderetourpourlacommande#'); //Demande d’autorisation de retour pour la commande
                    $patterns[] = array('pattern' => '#offredeacutesactiveacuteesenraisonduneerreurdeprixpotentielle#'); //Offre désactivées en raison d'une erreur de prix potentielle
                    $patterns[] = array('pattern' => '#votreemaila#'); // Votre e-mail à AUPEE
                    $patterns[] = array('pattern' => '#spam#'); // [SPAM]

                    $normalizedSubject = $this->normalizeSubject($email->subject);
                    $this->logger->info('--- start import email : '. $email->id);
                    $canImport = true;
                    foreach ($patterns as $pattern) {
                        if (preg_match($pattern['pattern'], $normalizedSubject)) {
                            $canImport = false;
                        }
                    }
                    $mpOrder = AmazonBeautifierMail::showCommandNumber($email->subject);
                    if ($canImport) {
                        $order   = Order::getOrder($mpOrder, $this->channel);
                        $ticket  = Ticket::getTicket($order, $this->channel);
                        $thread  = Thread::getOrCreateThread($ticket, $mpOrder, $email->subject, '', ['replyTo' => $email->fromAddress, 'fromName' => $email->fromName]);

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
            'API_URL'            => env('AMAZON_API_URL'),
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
            $this->logger->info('--- Get Email : '. $emailId);
            $emails[$emailId] = $this->mailbox->getMail($emailId,false);
        }
        return $emails;
    }

    /**
     * Normalize subject with lower case and only a -> z chars
     * @param string $subject
     * @return string
     */
    public function normalizeSubject(string $subject): string
    {
        return Stringer::normalize($subject);
    }

    /**
     * @param Ticket $ticket
     * @param Thread $thread
     * @param $messages
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
    protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread)
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
}
