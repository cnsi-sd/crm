<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\MessageDocumentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\TmpFile;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Attachments\Model\Document;
use Cnsi\Lock\Lock;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class IcozaImportMessages extends AbstractImportMessages
{
    private Client $client;

    const FROM_DATE_TRANSFORMATOR = ' - 2 hour';
    const version = '2011-09-01';

    const ALERT_LOCKED_SINCE = 1800;
    const KILL_LOCKED_SINCE = 3600;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->signature =sprintf($this->signature,'icoza');
        parent::__construct();
    }

    protected function getCredentials(): array
    {
        return [
            'host'  => env('ICOZA_API_URL'),
            'key'   => env('ICOZA_API_KEY'),
        ];
    }

    protected function initApiClient(): ?Client
    {
        $client = new Client([
            'headers' => [
                'token' => self::getCredentials()['key'],
                'Accept' => 'application/json',
            ],
        ]);
        $this->client = $client;

        return $this->client;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle()
    {
        $lock = new Lock($this->getName(), self::ALERT_LOCKED_SINCE, self::KILL_LOCKED_SINCE, env('ERROR_RECIPIENTS'));
        $lock->lock();

        // Load Channel
        $this->channel = Channel::getByName(ChannelEnum::ICOZA_FR);

        $this->logger = new Logger(
            'import_message/'
            . $this->channel->getSnakeName() . '/'
            . $this->channel->getSnakeName()
            . '.log', true, true
        );
        $this->logger->info('--- Start ---');

        // GET LAST MESSAGES
        $this->logger->info('Init api');
        $client = $this->initApiClient();

        $fromTime = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
        $fromDate = date('Y-m-d H:i:s', $fromTime);

        $messages = json_decode($client->request(
            'GET', $this->getCredentials()['host']. "GetMessages?after=". $fromDate
        )->getBody());

        $this->logger->info('Get messages');

        foreach ($messages->Messages as $message) {
            try {
                $starter_date = $this->checkMessageDate(new DateTime($message->date_add));
                if (!$starter_date)
                    continue;

                $order      = Order::getOrder($message->order, $this->channel);
                $ticket     = Ticket::getTicket($order, $this->channel);
                $thread     = Thread::getOrCreateThread($ticket, Thread::DEFAULT_CHANNEL_NUMBER, Thread::DEFAULT_NAME);

                if (!$this->isMessagesImported($message->id)) {
                    $this->logger->info('Convert api message to db message');
                    $this->convertApiResponseToMessage($ticket, $message, $thread);
                    $this->addImportedMessageChannelNumber($message->id);
                }

            } catch (Exception $e) {
                $this->logger->error('An error has occurred.', $e);
                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
            $this->logger->info('---- END ----');
        }
    }

    /**
     * @throws Exception
     */
    public function convertApiResponseToMessage(Ticket $ticket, $message_api, Thread $thread, $attachments = [])
    {
        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::OPENED;
        $ticket->save();
        $this->logger->info('Ticket save');

        $message = \App\Models\Ticket\Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message_api->id,
        ],
            [
                'user_id' => null,
                'direct_customer_email' => $message_api->email,
            // Messages authors are only customer on this API
                'author_type' => TicketMessageAuthorTypeEnum::CUSTOMER,
                'content' => strip_tags($message_api->content)
            ]);

        if($message_api->attachements) {
            $this->logger->info('Download documents from message');
            foreach ($message_api->attachements as $attachement) {
                $tmpFile = new TmpFile((string) file_get_contents($attachement->url));
                Document::doUpload($tmpFile, $message, MessageDocumentTypeEnum::OTHER, null, $attachement->image_name);
            }
        }

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }
}
