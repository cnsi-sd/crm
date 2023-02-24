<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Mirakl\MMP\Common\Domain\Message\Thread\Thread;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadTopic;
use Mirakl\MMP\OperatorShop\Request\Message\GetThreadsRequest;
use Mirakl\MMP\Shop\Client\ShopApiClient;

abstract class AbstractMiraklImportMessages extends AbstractImportMessages
{
    protected Logger $logger;
    protected string $log_path;

    public ShopApiClient $client;

    const FROM_DATE_TRANSFORMATOR = ' -  2 hours';
    const HTTP_CONNECT_TIMEOUT = 15;

    const FROM_SHOP_TYPE = 'SHOP_USER';

    abstract protected function getChannelName(): string;

    /**
     * @throws Exception
     */
    public function handle()
    {
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
                DB::beginTransaction();
                $this->logger->info('Begin Transaction');

                $mpOrderId = $this->getMarketplaceOrderIdFromThreadEntities($miraklThread->getEntities()->getIterator());
                $order = Order::getOrder($mpOrderId, $this->channel);
                $ticket = Ticket::getTicket($order, $this->channel);

                $this->logger->info('Message recovery');
                /** @var ThreadMessage[] $messages */
                $messages = array_reverse($miraklThread->getMessages()->getItems());

                /** @var ThreadTopic $topic */
                $thread = \App\Models\Ticket\Thread::getOrCreateThread($ticket, $miraklThread->getId(), $miraklThread->getTopic()->getValue(), '');

                $this->importMessageByThread($ticket, $thread, $messages);

                DB::commit();
            } catch (Exception $e) {
                $this->logger->error('An error has occurred. Rolling back.', $e);
                DB::rollBack();
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
     * @param  $messages
     * @return void
     * @throws Exception
     */
    private function importMessageByThread(Ticket $ticket, \App\Models\Ticket\Thread $thread, $messages): void
    {
        foreach ($messages as $message) {
            $imported_id = $message->getId();
            $this->logger->info('Check if this message is imported');
            if (!$this->isMessagesImported($imported_id)) {
                $this->logger->info('Convert api message to db message');
                $this->convertApiResponseToMessage($ticket, $message, $thread);
                $this->addImportedMessageChannelNumber($imported_id);
            }
        }
    }

    /**
     * Convert api messages into message model in order to save it in database
     * @param Ticket $ticket
     * @param ThreadMessage $message_api
     * @param \App\Models\Ticket\Thread $thread
     */
    public function convertApiResponseToMessage(Ticket $ticket, $message_api, \App\Models\Ticket\Thread $thread)
    {
        $authorType = $message_api->getFrom()->getType();
        $isNotShopUser = self::isNotShopUser($authorType, self::FROM_SHOP_TYPE);
        if ($isNotShopUser) {
            $this->logger->info('Set ticket\'s status to waiting admin');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            $this->logger->info('Ticket save');
            Message::firstOrCreate([
                'thread_id' => $thread->id,
                'channel_message_number' => $message_api->getId(),
            ],
                [
                    'thread_id' => $thread->id,
                    'user_id' => null,
                    'channel_message_number' => $message_api->getId(),
                    'author_type' => self::getAuthorType($authorType),
                    'content' => strip_tags($message_api->getBody()),
                ],
            );
            if (setting('autoReplyActivate')) {
                $this->logger->info('Send auto reply');
                self::sendAutoReply(setting('autoReply'), $thread);
            }
        }
    }

    protected function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'CUSTOMER_USER' => TicketMessageAuthorTypeEnum::CUSTOMER,
            default => TicketMessageAuthorTypeEnum::OPERATEUR,
        };
    }

}
