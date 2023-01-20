<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Models\Channel\Channel;
use App\Models\Channel\Order;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use FnacApiClient\Client\SimpleClient;
use FnacApiClient\Entity\Message;
use FnacApiClient\Service\Request\MessageQuery;
use FnacApiClient\Type\MessageType;
use Illuminate\Support\Facades\DB;

class FnacImportMessage extends AbstractImportMessage
{

    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'fnac');
        return parent::__construct();
    }
    protected Logger $logger;
    protected string $marketplace = 'fnac';

//    static private ?SimpleClient $client = null;
    public static mixed $_alreadyImportedMessages;

    protected function getChannelName(): string
    {
        //TODO pourquoi transformer le name en snake_case?
        return (new Channel)->getSnakeName(ChannelEnum::FNAC_COM);
    }
//    protected $signature = 'fnac:import:messagestest {--S|sync} {--T|thread=} {--only_best_prices} {--only_updated_offers} {--exclude_supplier=*} {--only_best_sellers} {--part=}';
//    protected $description = 'Importing competing offers from testing fnac.';

    protected function getCredentials(): array
    {
        return [
            'host'       => env('FNAC_API_URL'),
            'shop_id'    => env('FNAC_API_SHOP_ID'),
            'key'        => env('FNAC_API_KEY'),
            'partner_id' => env('FNAC_API_PARTNER_ID'),
        ];
    }

    /**
     * @throws Exception
     */
    protected function initApiCLient(): ?SimpleClient
    {
        if(self::$client == null) {
            $client = new SimpleClient();

//            $this->logger = new Logger('import_message/' . $this->getChannelName() . '/' . $this->getChannelName() . '.log', true, true);
            $client->init(self::getCredentials());
            $client->checkAuth();

            self::$client = $client;
        }

        return self::$client;
    }

    protected function getMessageApiId($message): string
    {
        return $message->getMessageId();
    }

    protected function getMpOrderApiId($message): string|array
    {
        // TODO: Implement getMpOrderApiId() method.
    }

    /**
     * @throws Exception
     */
    public function handle()
    {

        $channel_thread_number = 'fnac_default';

//        $this->logger = new Logger(
//            'import_message/'
//            . $this->getChannelName() . '/'
//            . $this->getChannelName()
//            . '.log', true, true
//        );
//        $this->logger->info('--- Start ---');

        //GET LAST MESSAGES
//        $this->logger->info('Init api');
        $client = self::initApiClient();

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
                DB::beginTransaction();
                $messageId  = $this->getMessageApiId($message);

                // TODO abstract these 4 or 5 lines ?
                $mpOrderId  = $this->getMpOrderApiId($message);
                $channel    = Channel::getByName(ChannelEnum::FNAC_COM); // Channel = mp
                $order      = Order::getOrder($mpOrderId, $channel);
                $ticket     = Ticket::getTicket($order, $channel);

                $thread     = Thread::getOrCreateThread($ticket, $channel_thread_number, $channel->name, '');

//                if (!$this->isMessageImported($messageId)) {
//                    $this->logger->info('Convert api message to db message');
//                    $this->convertApiResponseMessage($ticket, $message, $thread);
//                    $this->addImportedMessageChannelNumber($messageId);
//                }

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
     * @throws Exception
     */
    private function isMessageImported(string $channel_message_number): bool
    {
        if (!self::$_alreadyImportedMessages) {
            self::$_alreadyImportedMessages = \App\Models\Ticket\Message::query()
                ->select('channel_message_number')
                ->join('ticket_threads', 'ticket_threads.id', '=', 'ticket_thread_messages.thread_id') // thread
                ->join('tickets', 'tickets.id', '=', 'ticket_threads.ticket_id') // ticket
                ->where('channel_id', Channel::getByName(ChannelEnum::FNAC_COM))
                ->get()
                ->pluck('channel_message_number', 'channel_message_number')
                ->toArray();
        }

        return isset(self::$_alreadyImportedMessages[$channel_message_number]);
    }

    public function convertApiResponseMessage(Ticket $ticket, Message $message, Thread $thread)
    {
        $authorType = $message->getMessageFromType();
        $isNotShopUser = self::isNotShopUser($authorType);

        if($isNotShopUser) {
            $this->logger->info('Set ticket\'s status to waiting admin');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            $this->logger->info('Ticket save');

            \App\Models\Ticket\Message::firstOrCreate([
                'thread_id' => $thread->id,
                'channel_message_number' => $message->getMessageId(),
            ],
            [
                'thread_id' => $thread->id,
                'user_id' => null,
                'channel-message_number' => $message->getMessageId(),
                'author_type' => self::getAuthorType($authorType),
                'content' => strip_tags($message->getMessageDescription())
            ]);
        }
    }

//    const FROM_SHOP_TYPE = [
//        'SHOP_USER',
//        'CALLCENTER',
//        ];

    /**
//     * returns if the message type is SHOP_USER
//     * @param string $type
//     * @return bool
//     */
//    private static function isNotShopUser(string $type): bool
//    {
//        return !in_array($type, self::FROM_SHOP_TYPE);
//    }
//    private static function getAuthorType(string $authorType): string
//    {
//        return match ($authorType) {
//            'CUSTOMER_USER' => TicketMessageAuthorTypeEnum::CUSTOMER,
//            'CLIENT'        => TicketMessageAuthorTypeEnum::CLIENT,
//            'CALLCENTER'    => TicketMessageAuthorTypeEnum::CALLCENTER,
//            default         => TicketMessageAuthorTypeEnum::OPERATEUR,
//        };
//    }

//    /**
//     * @param string $channel_message_number
//     * @return void
//     */
//    private function addImportedMessageChannelNumber(string $channel_message_number): void
//    {
//        self::$_alreadyImportedMessages[$channel_message_number] = $channel_message_number;
//    }
}
