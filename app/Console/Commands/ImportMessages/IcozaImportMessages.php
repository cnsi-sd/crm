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

class IcozaImportMessages extends AbstractImportMessages
{
    private string $FROM_SHOP_TYPE;
    private Client $client;

    const FROM_DATE_TRANSFORMATOR = ' - 2 hour';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->signature =sprintf($this->signature,'icoza');
        $this->FROM_SHOP_TYPE = 'ADMIN';
        return parent::__construct();
    }

    protected function getCredentials(): array
    {
        return [
            'host'  => env('ICOZA_API_URL'),
            'key'   => env('ICOZA_API_KEY'),
        ];
    }

    protected function initApiClient(): ?Client
    {
        $client = new Client([
            'headers' => [
                'token' => self::getCredentials()['key'],
                'Accept' => 'application/json',
            ],
        ]);
        $this->client = $client;

        return $this->client;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle()
    {
        // Load Channel
        $this->channel = Channel::getByName(ChannelEnum::ICOZA_FR);

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

        $fromTime = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
        $fromDate = date('Y-m-d H:i:s', $fromTime);

        $messages = json_decode($client->request(
            'GET', $this->getCredentials()['host']. "GetMessages?after=". $fromDate
        )->getBody());

        $this->logger->info('Get messages');

        foreach ($messages->Messages as $message) {
            try {
                DB::beginTransaction();

                $order      = Order::getOrder($message->order, $this->channel);
                $ticket     = Ticket::getTicket($order, $this->channel);
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
                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
        }
    }

    public function convertApiResponseToMessage(Ticket $ticket, $message_api, Thread $thread)
    {
        $authorType = TicketMessageAuthorTypeEnum::CUSTOMER;
        $isNotShopUser = self::isNotShopUser($authorType, $this->FROM_SHOP_TYPE);

        if($isNotShopUser) {
            $this->logger->info('Set ticket\'s status to waiting admin');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            $this->logger->info('Ticket save');

            \App\Models\Ticket\Message::firstOrCreate([
                'thread_id' => $thread->id,
                'channel_message_number' => $message_api->id,
            ],
                [
                    'thread_id' => $thread->id,
                    'user_id' => null,
                    'channel_message_number' => $message_api->id,
                    'direct_customer_email' => $message_api->email,
                    'author_type' => self::getAuthorType($authorType),
                    'content' => strip_tags($message_api->content)
                ]);
            if (setting('autoReplyActivate')) {
                $this->logger->info('Send auto reply');
                self::sendAutoReply(setting('autoReply'), $thread);
            }
        }
    }

    protected function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            default => TicketMessageAuthorTypeEnum::CUSTOMER,
        };
    }

}
