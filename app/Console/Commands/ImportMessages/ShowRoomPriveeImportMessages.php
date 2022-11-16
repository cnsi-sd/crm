<?php

namespace App\Console\Commands\ImportMessages;

use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Ticket;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Mirakl\MMP\Common\Domain\Message\Thread\Thread;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadTopic;
use Mirakl\MMP\OperatorShop\Request\Message\GetThreadsRequest;
use Mirakl\MMP\Shop\Client\ShopApiClient;

class ShowRoomPriveeImportMessages extends Command
{
    private ShopApiClient $client;
    const FROM_DATE_TRANSFORMATOR = ' -  1 days';
    const FROM_SHOP_TYPE = 'SHOP_USER';
    const HTTP_CONNECT_TIMEOUT = 15;

    protected $signature = 'showroom:import:messages';
    protected $description = 'import ShowRoom privée message';

    protected $channelId = 1;

    protected static $_alreadyImportedMessages;

    //protected static $_alreadyExisteInDB;

    private function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('SHOWROOM_API_URL'),
            env('SHOWROOM_API_KEY'),
            env('SHOWROOM_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }

    private function ifIsShopUser($type): bool
    {
        if (self::FROM_SHOP_TYPE === $type) {
            return false;
        } else {
            return true;
        }
    }

    protected function getMarketplaceOrderIdFromThreadEntities($entityIterator)
    {
        if ($entityIterator->current()->getType() == 'MMP_ORDER')
            return $entityIterator->current()->getId();
        if (!empty($entityIterator->next()))
            $this->getMarketplaceOrderIdFromThreadEntities($entityIterator);
        return null;
    }


    private function importMessage()
    {
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
        $i = 0;
        /** @var Thread $thread */
        foreach ($threads as $thread) {
            /** @var ThreadTopic $topic */

            try {
                $mpOrderId = $this->getMarketplaceOrderIdFromThreadEntities($thread->getEntities()->getIterator());
                /** @var ThreadMessage $message */
                $messages = array_reverse($thread->getMessages()->getItems());
                foreach ($messages as $message) {
                    $imported_id = $message->getId();
                    if (!$this->isMessagesImported($imported_id)) {
                        $this->convertApiResponseToModel($message, $thread->getId());
                        $this->addImportedMessageChannelNumber($imported_id);
                    }
                }
            } catch (Exception $e) {
                echo 'Error : ' . $e->getMessage() . "\n";
            }
        }

        //return $threads ;
    }

    private function importMessageByThread(\App\Models\Ticket\Thread $thread, $messages)
    {

        foreach ($messages as $message) {
            //print_r(strip_tags($message->getBody()). "\n");
            $imported_id = $message->getId();
            if (!$this->isMessagesImported($imported_id)) {
                //$this->convertApiResponseToModel($message, $thread->id);
                Message::convertApiResponseToModel($message, $thread->id);
                $this->addImportedMessageChannelNumber($imported_id);
            }
        }
    }

    private function importThread()
    {
        $date_time = new DateTime();
        $date_time->modify(self::FROM_DATE_TRANSFORMATOR);

        $request = new GetThreadsRequest();
        $request->setUpdatedSince($date_time);
        //$request->setEntityType('MMP_ORDER');
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


        /** @var Thread $thread */
        foreach ($threads as $thread) {
            try {
                $mpOrderId = $this->getMarketplaceOrderIdFromThreadEntities($thread->getEntities()->getIterator());
                $order = Order::createOrder($mpOrderId);
                //$order = $this->creatOrder($mpOrderId);
                //$ticket = $this->createTicket($order, $this->channelId, "waiting customer", "P1", "2022-11-09", 1);
                $ticket = Ticket::createTicket($order, $this->channelId, "waiting customer", "P1", "2022-11-09", 1);

                /** @var ThreadMessage[] $messages */
                $messages = array_reverse($thread->getMessages()->getItems());

                /** @var ThreadTopic $topic */
                $t = new \App\Models\Ticket\Thread();
                $t->ticket_id = $ticket->id;
                $t->name = $thread->getTopic()->getValue();
                $t->channel_thread_number = $thread->getId();
                $t->customer_issue = "";
                $t->created_at = $thread->getDateCreated();
                $t->updated_at = $thread->getDateUpdated();
                $t->save();

                $this->importMessageByThread($t, $messages);
            } catch (Exception $e) {
                echo 'Error : ' . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * @param string $orderId
     * @return Order
     */
    private function creatOrder(string $orderId)
    {
        return Order::firstOrCreate([
            'channel_order_number' => $orderId,
        ], [
            'channel_id' => $this->channelId,
            'channel_order_number' => $orderId,
        ]);
    }

    private function createTicket(Order $order, int $channelId, string $state, string $priority, $deadline, int $user)
    {
        return Ticket::firstOrCreate([
            'order_id' => $order->id,
        ], [
            'channel_id' => $channelId,
            'order_id' => $order->id,
            'state' => $state,
            'priority' => $priority,
            'deadline' => $deadline,
            'user_id' => $user
        ]);
    }

    private function isMessagesImported(string $channel_message_number): bool
    {
        if (!self::$_alreadyImportedMessages) {
            self::$_alreadyImportedMessages = Message::query()
                ->select('channel_message_number')
                ->join('ticket_threads', 'ticket_threads.id', '=', 'ticket_threads_messages.thread_id') // thread
                ->join('tickets', 'tickets.id', '=', 'ticket_threads.ticket_id') // ticket
                ->where('channel_id', $this->channelId)
                ->get()
                ->pluck('channel_message_number', 'channel_message_number')
                ->toArray();
        }
        return isset(self::$_alreadyImportedMessages[$channel_message_number]);
    }

    protected function addImportedMessageChannelNumber(string $channel_message_number)
    {
        self::$_alreadyImportedMessages[$channel_message_number] = $channel_message_number;
    }

    /**
     * @param ThreadMessage $api_message
     * @param $mp_order_id
     * @param $thread_id
     * @return Message
     */
    protected function convertApiResponseToModel($api_message, $thread_id): Message
    {
        $isShop_User = $this->ifIsShopUser($api_message->getFrom()->getType());

        $message = new Message();
        if (!$isShop_User) {
            $message = Message::firstOrCreate([
                'channel_message_number' => $api_message->getId(),
            ], [
                'thread_id' => $thread_id,
                'user_id' => 1,
                'channel_message_number' => $api_message->getId(),
                'author_type' => 'client',
                'content' => strip_tags($api_message->getBody()),
                'created_at' => $api_message->getDateCreated()->format('Y-m-d H:i:s'),
            ]);
        }
        return $message;
    }

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        //$this->initApiClient();
        dd($this->importThread());

        //dd($this->isImported('883c3c6f-4c53-4be1-8928-322f282ae70b'));

        return Command::SUCCESS;
    }
}
