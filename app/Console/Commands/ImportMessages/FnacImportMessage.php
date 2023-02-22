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
use FnacApiClient\Type\MessageFromType;
use FnacApiClient\Type\MessageType;
use Illuminate\Support\Facades\DB;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;

class FnacImportMessage extends AbstractImportMessage
{

    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'fnac');
        return parent::__construct();
    }
    protected Logger $logger;
    static private ?SimpleClient $client = null;

    /**
     * @throws Exception
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::FNAC_COM;
    }

    const FROM_SHOP_TYPE = [
        'SELLER'
    ];

    /**
     * @throws Exception
     */
    protected function getSnakeChannelName(): string
    {
        return (new Channel)->getSnakeName($this->getChannelName());
    }

    protected $description = 'Importing competing offers from testing Fnac.';

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

            $this->logger = new Logger('import_message/' . $this->getSnakeChannelName() . '/' . $this->getSnakeChannelName() . '.log', true, true);
            $client->init(self::getCredentials());
            $client->checkAuth();

            self::$client = $client;
        }

        return self::$client;
    }

    protected function getMessageApiId(Message|ThreadMessage $message): string
    {
        return $message->getMessageId();
    }

    protected function getMpOrderApiId($message, $thread = null)
    {
        return $message->getMessageReferer();
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->logger = new Logger(
            'import_message/'
            . $this->getSnakeChannelName() . '/'
            . $this->getSnakeChannelName()
            . '.log', true, true
        );
        $this->logger->info('--- Start ---');

        // GET LAST MESSAGES
        $this->logger->info('Init api');
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
                $mpOrderId  = $this->getMpOrderApiId($message);
                $channel    = Channel::getByName($this->getChannelName()); // Channel = mp
                $order      = Order::getOrder($mpOrderId, $channel);
                $ticket     = Ticket::getTicket($order, $channel);
                $thread     = Thread::getOrCreateThread($ticket, $message->getMessageReferer(), $message->getMessageSubject(), '');

                if (!$this->isMessagesImported($messageId)) {
                    $this->logger->info('Convert api message to db message');
                    $this->convertApiResponseToMessage($ticket, $message, $thread);
                    $this->addImportedMessageChannelNumber($messageId);
                }

                DB::commit();
            } catch (Exception $e) {
                $this->logger->error('An error has occurred. Rolling back.', $e);
                DB::rollBack();
                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
        }
    }

    public function convertApiResponseToMessage(Ticket $ticket, $message_api, Thread $thread)
    {
        $authorType = $message_api->getMessageFromType();
        $isNotShopUser = self::isNotShopUser($authorType);

        if($isNotShopUser) {
            $this->logger->info('Set ticket\'s status to waiting admin');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            $this->logger->info('Ticket save');

            \App\Models\Ticket\Message::firstOrCreate([
                'thread_id' => $thread->id,
                'channel_message_number' => $message_api->getMessageId(),
            ],
            [
                'thread_id' => $thread->id,
                'user_id' => null,
                'channel-message_number' => $message_api->getMessageId(),
                'author_type' => self::getAuthorType($authorType),
                'content' => strip_tags($message_api->getMessageDescription())
            ]);
            if (setting('autoReplyActivate')) {
                $this->logger->info('Send auto reply');
                self::sendAutoReply(setting('autoReply'), $thread);
            }
        }
    }

    /**
     * returns if the message type is SHOP_USER
     * @param string $type
     * @return bool
     */
    private static function isNotShopUser(string $type): bool
    {
        return !in_array($type, self::FROM_SHOP_TYPE);
    }
    protected function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'CLIENT'        => TicketMessageAuthorTypeEnum::CLIENT,
            'CALLCENTER'    => TicketMessageAuthorTypeEnum::CALLCENTER,
        };
    }
}
