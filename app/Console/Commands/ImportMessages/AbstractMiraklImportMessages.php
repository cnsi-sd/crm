<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\MessageDocumentTypeEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Helpers\TmpFile;
use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Ticket;
use Cnsi\Lock\Lock;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Mirakl\MMP\Common\Domain\Message\Thread\Thread;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadTopic;
use Mirakl\MMP\Common\Request\Message\DownloadThreadMessageAttachmentRequest;
use Mirakl\MMP\OperatorShop\Request\Message\GetThreadsRequest;
use Mirakl\MMP\Shop\Client\ShopApiClient;
use Cnsi\Attachments\Model\Document;

abstract class AbstractMiraklImportMessages extends AbstractImportMessages
{
    public ShopApiClient $client;

    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';
    const HTTP_CONNECT_TIMEOUT = 15;

    const ALERT_LOCKED_SINCE = 600;
    const KILL_LOCKED_SINCE = 1200;

    abstract protected function getChannelName(): string;

    /**
     * @throws Exception
     */
    public function handle()
    {
        $lock = new Lock($this->getName(), self::ALERT_LOCKED_SINCE, self::KILL_LOCKED_SINCE, env('ERROR_RECIPIENTS'));
        $lock->lock();

        $this->channel = Channel::getByName($this->getChannelName());
        $this->logger = new Logger('import_message/'
            . $this->channel->getSnakeName() . '/'
            . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');
        try {
            $date_time = new DateTime();
            $date_time->modify(self::FROM_DATE_TRANSFORMATOR);

            $request = new GetThreadsRequest();
            $request->setUpdatedSince($date_time);
            $request->setWithMessages(true);
            $this->logger->info('Init api');
            $client = $this->initApiClient();

            $this->logger->info('Get all thread');
            $threads = [];
            do {
                $response = $client->getThreads($request);
                foreach ($response->getCollection()->getItems() as $thread) {
                    $threads[] = $thread;
                }
                $nextToken = $response->getNextPageToken();
                $request->setPageToken($nextToken);
            } while ($nextToken);
        } catch (Exception $e) {
            $this->logger->error('An error has occurred on API call', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
        /** @var Thread $miraklThread */
        foreach ($threads as $miraklThread) {
            try {
                $this->logger->info('Message recovery');
                /** @var ThreadMessage[] $messages */
                $messages = array_reverse($miraklThread->getMessages()->getItems());

                if (count($messages) == 0)
                    continue;

                foreach ($messages as $message) {

                    $starter_date = $this->checkMessageDate($message->getDateCreated());
                    if (!$starter_date)
                        continue;

                    $authorType = $message->getFrom()->getType();

                    if ($authorType == 'SHOP_USER')
                        continue;

                    $mpOrderId = $this->getMarketplaceOrderIdFromThreadEntities($miraklThread->getEntities()->getIterator());
                    $order = Order::getOrder($mpOrderId, $this->channel);
                    $ticket = Ticket::getTicket($order, $this->channel);

                    /** @var ThreadTopic $topic */
                    $thread = \App\Models\Ticket\Thread::getOrCreateThread($ticket, $miraklThread->getId(), $miraklThread->getTopic()->getValue());

                    $attachments = [];
                    if($message->getAttachments()) {
                        $this->logger->info('Get attachments for message');
                        foreach ($message->getAttachments() as $attachment) {
                            $request = new DownloadThreadMessageAttachmentRequest($attachment->getData()["id"]);
                            $result = $client->downloadThreadMessageAttachment($request);
                            $attachments[] = $result;
                        }
                    }

                    $this->importMessageByThread($ticket, $thread, $message, $attachments);
                }
            } catch (Exception $e) {
                $this->logger->error('An error has occurred', $e);

                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
        }
    }

    /**
     * @return ShopApiClient
     */
    protected function initApiClient(): ShopApiClient
    {
        $credentials = $this->getCredentials();
        $this->client = new ShopApiClient(
            $credentials['API_URL'],
            $credentials['API_KEY'],
            $credentials['API_SHOP_ID'],
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }

    private function getMarketplaceOrderIdFromThreadEntities($entityIterator)
    {
        $this->logger->info('Get Market place id from thread');
        if ($entityIterator->current()->getType() == 'MMP_ORDER')
            return $entityIterator->current()->getId();

        if (!empty($entityIterator->next()))
            $this->getMarketplaceOrderIdFromThreadEntities($entityIterator);

        return null;
    }

    /**
     * @param Ticket $ticket
     * @param \App\Models\Ticket\Thread $thread
     * @param $message
     * @return void
     * @throws Exception
     */
    private function importMessageByThread(Ticket $ticket, \App\Models\Ticket\Thread $thread, $message, $attachments): void
    {
        $imported_id = $message->getId();
        $this->logger->info('Check if this message is imported');
        if ($this->isMessagesImported($imported_id))
            return;

        $this->logger->info('Convert api message to db message');
        $this->convertApiResponseToMessage($ticket, $message, $thread, $attachments);
        $this->addImportedMessageChannelNumber($imported_id);

    }

    /**
     * Convert api messages into message model in order to save it in database
     * @param Ticket $ticket
     * @param ThreadMessage $message_api
     * @param \App\Models\Ticket\Thread $thread
     * @throws Exception
     */
    public function convertApiResponseToMessage(Ticket $ticket, $message_api, \App\Models\Ticket\Thread $thread, $attachments = [])
    {
        $authorType = $message_api->getFrom()->getType();

        $this->logger->info('Set ticket\'s status to waiting admin');
        $ticket->state = TicketStateEnum::OPENED;
        $ticket->save();
        $this->logger->info('Ticket save');
        $message = Message::firstOrCreate([
            'thread_id' => $thread->id,
            'channel_message_number' => $message_api->getId(),
        ],
            [
                'user_id' => null,
                'author_type' => $this->getAuthorType($authorType),
                'content' => strip_tags($message_api->getBody()),
            ]
        );

        if($attachments) {
            $this->logger->info('Download documents from message');
            foreach($attachments as $attachment){
                $tmpFile = new TmpFile((string) $attachment->getFile()->fread($attachment->getFile()->fstat()['size']));
                Document::doUpload($tmpFile, $message, MessageDocumentTypeEnum::OTHER, null, utf8_encode($attachment->getFileName()));
            }
        }

        // Dispatch the job that will try to answer automatically to this new imported
        AnswerToNewMessage::dispatch($message);
    }

    /**
     * @throws Exception
     */
    protected function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'CUSTOMER_USER' => TicketMessageAuthorTypeEnum::CUSTOMER,
            'OPERATOR_USER' => TicketMessageAuthorTypeEnum::OPERATOR,
            default => throw new Exception('Bad author type')
        };
    }
}
