<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Jobs\SendMessage\ButSendMessage;
use App\Jobs\SendMessage\CarrefourSendMessage;
use App\Jobs\SendMessage\ConforamaSendMesssage;
use App\Jobs\SendMessage\DartySendMessage;
use App\Jobs\SendMessage\IntermarcheSendMessage;
use App\Jobs\SendMessage\LaposteSendMessage;
use App\Jobs\SendMessage\LeclercSendMessage;
use App\Jobs\SendMessage\MetroSendMessage;
use App\Jobs\SendMessage\RueDuCommerceSendMessage;
use App\Jobs\SendMessage\ShowroomSendMessage;
use App\Jobs\SendMessage\UbaldiSendMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
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
        // import_message/but_fr/but_fr_2022_10_03.log
        $this->logger = new Logger('import_message/' . $this->getChannelName() . '/' . $this->getChannelName() . '.log', true, true);
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
                $channel = Channel::getByName($this->getChannelName());
                $order = Order::getOrder($mpOrderId, $channel);
                $ticket = Ticket::getTicket($order, $channel);

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

    const FROM_SHOP_TYPE = 'SHOP_USER';

    /**
     * returns if the message type is SHOP_USER
     * @param string $type
     * @return bool
     */
    private static function isNotShopUser(string $type): bool
    {
        return self::FROM_SHOP_TYPE !== $type;
    }

    /**
     * @param Ticket $ticket
     * @param \App\Models\Ticket\Thread $thread
     * @param  $messages
     * @return void
     * @throws Exception
     */
    private function importMessageByThread(Ticket $ticket, \App\Models\Ticket\Thread $thread, $messages)
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
     * @param ThreadMessage $api_message
     * @param \App\Models\Ticket\Thread $thread
     */
    public function convertApiResponseToMessage(Ticket $ticket, ThreadMessage $api_message, \App\Models\Ticket\Thread $thread)
    {
        $authorType = $api_message->getFrom()->getType();
        $isNotShopUser = self::isNotShopUser($authorType);
        if ($isNotShopUser) {
            $this->logger->info('Set ticket\'s status to wating admin');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            $this->logger->info('Ticket save');
            Message::firstOrCreate([
                'thread_id' => $thread->id,
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
            if (setting('autoReplyActivate')) {
                $this->logger->info('Send auto reply');
                self::sendAutoReply(setting('autoReply'), $thread);
            }
        }
    }

    private static function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'CUSTOMER_USER' => TicketMessageAuthorTypeEnum::CUSTOMER,
            default => TicketMessageAuthorTypeEnum::OPERATEUR,
        };
    }

    /**
     * @param string $channel_message_number
     * @return bool
     * @throws Exception
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

    /**
     * @param mixed $messageId
     * @param \App\Models\Ticket\Thread $thread
     * @return void
     */
    public function sendAutoReply(mixed $messageId, \App\Models\Ticket\Thread $thread): void
    {
        $autoReplyContentWeek = DefaultAnswer::query()->select('content')->where('id', $messageId)->first();

        $autoReply = new Message();
        $autoReply->thread_id = $thread->id;
        $autoReply->user_id = null;
        $autoReply->channel_message_number = '';
        $autoReply->author_type = TicketMessageAuthorTypeEnum::ADMIN;
        $autoReply->content = $autoReplyContentWeek['content'];
        $autoReply->save();
        $this->logger->info('Auto reply save in db');

        $this->logger->info('Send queue execution');
        match ($thread->ticket->channel->name) {
            ChannelEnum::BUT_FR => ButSendMessage::dispatch($autoReply),
            ChannelEnum::CARREFOUR_FR => CarrefourSendMessage::dispatch($autoReply),
            ChannelEnum::CONFORAMA_FR => ConforamaSendMesssage::dispatch($autoReply),
            ChannelEnum::DARTY_COM => DartySendMessage::dispatch($autoReply),
            ChannelEnum::INTERMARCHE_FR => IntermarcheSendMessage::dispatch($autoReply),
            ChannelEnum::LAPOSTE_FR => LaposteSendMessage::dispatch($autoReply),
            ChannelEnum::E_LECLERC => LeclercSendMessage::dispatch($autoReply),
            ChannelEnum::METRO_FR => MetroSendMessage::dispatch($autoReply),
            ChannelEnum::RUEDUCOMMERCE_FR => RueDuCommerceSendMessage::dispatch($autoReply),
            ChannelEnum::SHOWROOMPRIVE_COM => ShowroomSendMessage::dispatch($autoReply),
            ChannelEnum::UBALDI_COM => UbaldiSendMessage::dispatch($autoReply),
        };
    }
}
