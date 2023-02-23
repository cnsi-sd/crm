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
use FnacApiClient\Entity\Message;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;

class IcozaImportMessage extends AbstractImportMessages
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->signature =sprintf($this->signature,'icoza');
        $this->channel = Channel::getByName(ChannelEnum::ICOZA_FR);
        return parent::__construct();
    }

    protected function getCredentials(): array
    {
        return [
            'host'  => env('ICOZA_API_URL'),
            'key'   => env('ICOZA_API_KEY'),
        ];
    }
    const FROM_DATE_TRANSFORMATOR = ' - 2 hour';

    protected function getMessageApiId(ThreadMessage|Message $message): string
    {
        // TODO: Implement getMessageApiId() method.
    }

    protected function getMpOrderApiId($message, $thread = null)
    {
        // TODO: Implement getMpOrderApiId() method.
    }

    protected Logger $logger;
    static private ?Client $client = null;
    const FROM_SHOP_TYPE = [
        TicketMessageAuthorTypeEnum::ADMIN
    ];

    /**
     * @throws Exception
     */
    protected function getSnakeChannelName(): string
    {
        return $this->channel->getSnakeName($this->channel->name);
    }

    protected function initApiClient(): ?Client
    {
        if(self::$client == null) {

            $client = new Client([
                'headers' => [
                    'token' => self::getCredentials()['key'],
                    'Accept' => 'application/json',
                ],
            ]);
            self::$client = $client;
        }

        return self::$client;
    }

    /**
     * @throws GuzzleException
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

        $fromTime = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
        $fromDate = date('Y-m-d H:i:s', $fromTime);

        $messages = json_decode($client->request(
            'GET', $this->getCredentials()['host']. "GetMessages?after=". $fromDate
        )->getBody());

        $this->logger->info('Get messages');

        foreach ($messages->Messages as $message) {
            try {
                DB::beginTransaction();

                $channel    = $this->channel; // Channel = mp
                $order      = Order::getOrder($message->order, $channel);
                $ticket     = Ticket::getTicket($order, $channel);
                $thread     = Thread::getOrCreateThread($ticket, $message->order, 'Icoza sujet', '');

                if (!$this->isMessagesImported($message->id)) {
                    $this->logger->info('Convert api message to db message');
                    $this->convertApiResponseToMessage($ticket, $message, $thread);
                    $this->addImportedMessageChannelNumber($message->id);
                }

                DB::commit();
                $this->logger->info('---- END ----');
            } catch (Exception $e) {
                $this->logger->error('An error has occurred. Rolling back.', $e);
                DB::rollBack();
//                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
        }
    }

    public function convertApiResponseToMessage(Ticket $ticket, $message, Thread $thread)
    {
        $authorType = TicketMessageAuthorTypeEnum::CUSTOMER;
        $isNotShopUser = self::isNotShopUser($authorType);

        if($isNotShopUser) {
            $this->logger->info('Set ticket\'s status to waiting admin');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            $this->logger->info('Ticket save');

            \App\Models\Ticket\Message::firstOrCreate([
                'thread_id' => $thread->id,
                'channel_message_number' => $message->id,
            ],
                [
                    'thread_id' => $thread->id,
                    'user_id' => null,
                    'channel_message_number' => $message->id,
                    'direct_customer_email' => $message->email,
                    'author_type' => self::getAuthorType($authorType),
                    'content' => strip_tags($message->content)
                ]);
//            if (setting('autoReplyActivate')) {
//                $this->logger->info('Send auto reply');
//                self::sendAutoReply(setting('autoReply'), $thread);
//            }
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
            default => TicketMessageAuthorTypeEnum::CUSTOMER,
        };
    }

}
