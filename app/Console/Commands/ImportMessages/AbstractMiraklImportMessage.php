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
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Mirakl\MMP\Common\Domain\Message\Thread\Thread;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadTopic;
use Mirakl\MMP\OperatorShop\Request\Message\GetThreadsRequest;
use Mirakl\MMP\Shop\Client\ShopApiClient;

abstract class AbstractMiraklImportMessage extends Command
{
    protected Logger $logger;
    protected string $log_path;

    public ShopApiClient $client;

    const FROM_DATE_TRANSFORMATOR = ' -  2 hours';
    const HTTP_CONNECT_TIMEOUT = 15;

    protected static $_alreadyImportedMessages;

    protected $signature = '%s:import:messages {--S|sync} {--T|thread=} {--only_best_prices} {--only_updated_offers} {--exclude_supplier=*} {--only_best_sellers} {--part=}';
    protected $description = 'Importing competing offers from Mirakl.';

    abstract protected function getChannelName(): string;
    abstract protected function getCredentials(): array;

    public function handle()
    {
        $this->logger = new Logger($this->log_path);
        $date_time = new DateTime();
        $date_time->modify(self::FROM_DATE_TRANSFORMATOR);

        $request = new GetThreadsRequest();
        $request->setUpdatedSince($date_time);
        $request->setWithMessages(true);

        $client = $this->initApiClient();

        $threads = [];
        do {
            $response = $client->getThreads($request);
            foreach ($response->getCollection()->getItems() as $thread) {
                $threads[] = $thread;
            }
            $nextToken = $response->getNextPageToken();
            $request->setPageToken($nextToken);
        } while ($nextToken);

        /** @var Thread $miraklThread */
        foreach ($threads as $miraklThread) {
            try {
                DB::beginTransaction();
                $this->logger->info("begin Transaction");
                $mpOrderId = $this->getMarketplaceOrderIdFromThreadEntities($miraklThread->getEntities()->getIterator());
                $channel = Channel::getByName($this->getChannelName());
                $order = Order::getOrder($mpOrderId, $channel);
                $ticket = Ticket::getTicket($order, $channel);

                /** @var ThreadMessage[] $messages */
                $messages = array_reverse($miraklThread->getMessages()->getItems());

                /** @var ThreadTopic $topic */
                $thread = \App\Models\Ticket\Thread::getThread($ticket, $miraklThread->getId(), $miraklThread->getTopic()->getValue(), '');

                $this->importMessageByThread($ticket, $thread, $messages);

                DB::commit();
            } catch (Exception $e) {
                $this->logger->error("Error", $e);
                $errorOutput = 'An error has occurred. Rolling back';
                $this->error($errorOutput);
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
        if ($entityIterator->current()->getType() == 'MMP_ORDER')
            return $entityIterator->current()->getId();

        if (!empty($entityIterator->next()))
            $this->getMarketplaceOrderIdFromThreadEntities($entityIterator);

        return null;
    }

    const FROM_SHOP_TYPE = 'SHOP_USER';

    /**
     * @param string $type
     * @return bool
     */
    private static function isNotShopUser(string $type): bool
    {
        return self::FROM_SHOP_TYPE !== $type;
    }

    /**
     * @param \App\Models\Ticket\Thread $thread
     * @param  $messages
     * @return void
     */
    private function importMessageByThread(Ticket $ticket,\App\Models\Ticket\Thread $thread, $messages)
    {
        foreach ($messages as $message) {
            $imported_id = $message->getId();
            if (!$this->isMessagesImported($imported_id)) {
                $this->convertApiResponseToMessage($ticket, $message, $thread);
                $this->addImportedMessageChannelNumber($imported_id);
            }
        }
    }

    /**
     * @param ThreadMessage $api_message
     * @param \App\Models\Ticket\Thread $thread
     * @return Message
     */
    public static function convertApiResponseToMessage(Ticket $ticket, ThreadMessage $api_message, \App\Models\Ticket\Thread $thread): Message
    {
        $authorType = $api_message->getFrom()->getType();

        $isShopUser = self::isNotShopUser($authorType);

        $message = new Message();
        if ($isShopUser) {
            if($ticket->state !== TicketStateEnum::WAITING_ADMIN){
                $ticket->state = TicketStateEnum::WAITING_ADMIN;
            }
            $message = Message::firstOrCreate([
                'channel_message_number' => $api_message->getId(),
            ],
                [
                    'thread_id' => $thread->id,
                    'user_id' => null,
                    'channel_message_number' => $api_message->getId(),
                    'author_type' => self::getAuthorType($authorType),
                    'content' => strip_tags($api_message->getBody()),
                ],
            );
        }
        return $message;
    }

    private static function getAuthorType(string $authorType){
        switch ($authorType){
            case('CUSTOMER_USER'):
                return TicketMessageAuthorTypeEnum::CUSTOMER;
                break;
            default:
                return TicketMessageAuthorTypeEnum::OPERATEUR;
                break;
        }
    }

    /**
     * @param string $channel_message_number
     * @return bool
     * @throws \Exception
     */
    private function isMessagesImported(string $channel_message_number): bool
    {
        if (!self::$_alreadyImportedMessages) {
            self::$_alreadyImportedMessages = Message::query()
                ->select('channel_message_number')
                ->join('ticket_threads', 'ticket_threads.id', '=', 'ticket_thread_messages.thread_id') // thread
                ->join('tickets', 'tickets.id', '=', 'ticket_threads.ticket_id') // ticket
                ->where('channel_id', Channel::getByName($this->getChannelName())->id)
                ->get()
                ->pluck('channel_message_number', 'channel_message_number')
                ->toArray();
        }
        return isset(self::$_alreadyImportedMessages[$channel_message_number]);
    }

    /**
     * @param string $channel_message_number
     * @return void
     */
    private function addImportedMessageChannelNumber(string $channel_message_number)
    {
        self::$_alreadyImportedMessages[$channel_message_number] = $channel_message_number;
    }

}
