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
use BompApiClient\Client\SimpleClient;
use BompApiClient\Entity\Message;
use BompApiClient\Exception\ErrorResponseException;
use BompApiClient\Service\Request\MessageQuery;
use BompApiClient\Type\MessageType;
use Cnsi\Attachments\Model\Document;
use Cnsi\Lock\Lock;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;

abstract class AbstractBompImportMessages extends AbstractImportMessages
{
    private SimpleClient $client;
    const ALERT_LOCKED_SINCE = 1800;
    const KILL_LOCKED_SINCE = 3600;
    abstract protected function getChannelName(): string;
    abstract protected function getCredentials(): array;

    /**
     * @throws ErrorResponseException
     */
    protected function initApiClient(): SimpleClient
    {
        $this->client = new SimpleClient();
        $this->client->init($this->getCredentials());
        $this->client->checkAuth();

        return $this->client;
    }

    /**
     * @throws Exception
     */
    protected function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'CLIENT'        => TicketMessageAuthorTypeEnum::CUSTOMER,
            'CALLCENTER'    => TicketMessageAuthorTypeEnum::OPERATOR,
            default => throw new Exception('Bad author type.')
        };
    }

    /**
     * @throws Exception
     */
    public function convertApiResponseToMessage(Ticket $ticket, $message_api, Thread $thread, $attachments = []): void
    {
        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::OPENED;
        $ticket->save();
        $this->logger->info('Ticket save');

        $authorType = $message_api->getMessageFromType();

        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::OPENED;
        $ticket->save();
        $this->logger->info('Ticket save');

        $message = \App\Models\Ticket\Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message_api->getMessageId(),
        ],
            [
                'user_id' => null,
                'author_type' => $this->getAuthorType($authorType),
                'content' => strip_tags($message_api->getMessageDescription())
            ]
        );
        if($message_api->getMessageFile()) {
            $this->logger->info('Download documents from message');
            $message_files = $message_api->getMessageFile();

            // Patch : if the message contains only one message, make an array
            if(!is_null($message_files) && isset($message_files['name'])) {
                $message_files = [ $message_files ];
            }

            foreach ($message_files as $message_file) {
                $tmpFile = new TmpFile((string) file_get_contents($message_file['url']));
                Document::doUpload($tmpFile, $message, MessageDocumentTypeEnum::OTHER, null, $message_file['name']);
            }
        }

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }

    /**
     * @throws ErrorResponseException
     * @throws Exception
     */
    public function handle()
    {
        $lock = new Lock($this->getName(), self::ALERT_LOCKED_SINCE, self::KILL_LOCKED_SINCE, env('ERROR_RECIPIENTS'));
        $lock->lock();

        // Load channel
        $this->channel = Channel::getByName(ChannelEnum::FNAC_COM);

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

        $query = new MessageQuery();
        $query->setMessageType(MessageType::ORDER);
        $messages = $client->callService($query);

        $this->logger->info('Get messages');
        /** @var Message[] $messages */
        $messages = $messages->getMessages()->getArrayCopy();

        // SORT MESSAGES IN A CHRONOLOGICAL SEQUENCE
        $messages = array_reverse((array)$messages);

        foreach ($messages as $message) {
            try {
                $starter_date = $this->checkMessageDate(new DateTime($message->getCreatedAt()));
                if (!$starter_date)
                    continue;

                $messageId  = $message->getMessageId();;
                $mpOrderId  = $message->getMessageReferer();;
                $order      = Order::getOrder($mpOrderId, $this->channel);
                $ticket     = Ticket::getTicket($order, $this->channel);
                $thread     = Thread::getOrCreateThread($ticket, Thread::DEFAULT_CHANNEL_NUMBER, $message->getMessageSubject());

                if (!$this->isMessagesImported($messageId)) {
                    $this->logger->info('Convert api message to db message');
                    $this->convertApiResponseToMessage($ticket, $message, $thread);
                    $this->addImportedMessageChannelNumber($messageId);
                }

            } catch (Exception $e) {
                $this->logger->error('An error has occurred.', $e);
                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
        }
    }
}
