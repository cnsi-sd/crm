<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Ticket\TicketMessageAuthorType;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Ticket;
use DateTime;
use Exception;
use Illuminate\Console\Command;
use Mirakl\MMP\Common\Domain\Collection\Message\Thread\ThreadRecipientCollection;
use Mirakl\MMP\Common\Domain\Message\Thread\Thread;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadRecipient;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadReplyMessageInput;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadTopic;
use Mirakl\MMP\Common\Request\Message\ThreadReplyRequest;
use Mirakl\MMP\OperatorShop\Request\Message\GetThreadsRequest;
use Mirakl\MMP\Shop\Client\ShopApiClient;

class ShowRoomPriveeImportMessages extends Command
{

    private ShopApiClient $client;

    const FROM_DATE_TRANSFORMATOR = ' -  2 hours';
    const HTTP_CONNECT_TIMEOUT = 15;

    protected $signature = 'showroom:import:messages';
    protected $description = 'import ShowRoom privée message';

    protected $channelId = 1;

    protected static $_alreadyImportedMessages;

    public function handle()
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


        /** @var Thread $miraklThread */
        foreach ($threads as $miraklThread) {
            try {
                $mpOrderId = $this->getMarketplaceOrderIdFromThreadEntities($miraklThread->getEntities()->getIterator());
                $channel = Channel::find(1);
                $order = Order::getOrder($mpOrderId, $channel);
                $ticket = Ticket::getTicket($order, $channel);

                /** @var ThreadMessage[] $messages */
                $messages = array_reverse($miraklThread->getMessages()->getItems());

                /** @var ThreadTopic $topic */
                $thread = \App\Models\Ticket\Thread::getThread($ticket, $miraklThread->getId(), $miraklThread->getTopic()->getValue(), '');

                $this->importMessageByThread($thread, $messages);
            } catch (Exception $e) {
                echo 'Error : ' . $e->getMessage() . "\n";
            }
        }
    }

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

    private function getMarketplaceOrderIdFromThreadEntities($entityIterator)
    {
        if ($entityIterator->current()->getType() == 'MMP_ORDER')
            return $entityIterator->current()->getId();

        if (!empty($entityIterator->next()))
            $this->getMarketplaceOrderIdFromThreadEntities($entityIterator);

        return null;
    }

    const FROM_SHOP_TYPE = 'SHOP_USER';

    private static function isShopUser($type): bool
    {
        return self::FROM_SHOP_TYPE !== $type;
    }


    private function importMessageByThread(\App\Models\Ticket\Thread $thread, $messages)
    {
        foreach ($messages as $message) {
            $imported_id = $message->getId();
            if (!$this->isMessagesImported($imported_id)) {
                $this->convertApiResponseToModel($message, $thread->id);
                $this->addImportedMessageChannelNumber($imported_id);
            }
        }
    }

    /**
     * @param ThreadMessage $api_message
     * @param $thread_id
     * @return Message
     */
    public static function convertApiResponseToModel($api_message, $thread_id): Message
    {
        $isShopUser = self::isShopUser($api_message->getFrom()->getType());

        $message = new Message();
        if (!$isShopUser) {
            $message = Message::firstOrCreate([
                'channel_message_number' => $api_message->getId(),
            ],
            [
                'thread_id' => $thread_id,
                'user_id' => 1, // TODO : null
                'channel_message_number' => $api_message->getId(),
                'author_type' => TicketMessageAuthorType::MESSAGE_OPERATEUR, // TODO : à faire (opérateur / client)
                'content' => strip_tags($api_message->getBody()),
            ],
            );
        }
        return $message;
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

    private function addImportedMessageChannelNumber(string $channel_message_number)
    {
        self::$_alreadyImportedMessages[$channel_message_number] = $channel_message_number;
    }
}
