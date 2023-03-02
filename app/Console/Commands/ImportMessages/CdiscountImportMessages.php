<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Enums\Ticket\TicketStateEnum;
use App\Jobs\SendMessage\ButSendMessage;
use App\Jobs\SendMessage\CarrefourSendMessage;
use App\Jobs\SendMessage\CdiscountSendMessage;
use App\Jobs\SendMessage\ConforamaSendMessage;
use App\Jobs\SendMessage\DartySendMessage;
use App\Jobs\SendMessage\IntermarcheSendMessage;
use App\Jobs\SendMessage\LaposteSendMessage;
use App\Jobs\SendMessage\LeclercSendMessage;
use App\Jobs\SendMessage\MetroSendMessage;
use App\Jobs\SendMessage\RueducommerceSendMessage;
use App\Jobs\SendMessage\ShowroomSendMessage;
use App\Jobs\SendMessage\UbaldiSendMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use App\Models\Channel\Order;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Cdiscount\ClientCdiscount;
use Cnsi\Cdiscount\Discussion\Discussion;
use Cnsi\Cdiscount\DiscussionsApi;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CdiscountImportMessages extends AbstractImportMessages
{
    /**
     * @var ClientCdiscount
     */
    static private ClientCdiscount $client;

    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';
    const MAX_RETRY_API_CALL = 5;
    const IGNORE_MSG_CONTAINS = [
        '----- The following addresses had permanent fatal errors -----',
        'THIS IS A WARNING MESSAGE ONLY',
        'THIS IS A WARNING ONLY.',
        'Votre demande d’annulation a été acceptée. Le remboursement est en cours.',
    ];
    private string $FROM_SHOP_TYPE;

    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'cdiscount');
        $this->FROM_SHOP_TYPE = 'Seller';
        return parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        $this->channel = Channel::getByName(ChannelEnum::CDISCOUNT_FR);

        $this->logger = new Logger('import_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');
        try {
            $from_time = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date('Y-m-d H:m:i', $from_time);

            $this->logger->info('--- Init api client ---');
            $this->initApiClient();
            $discussion = new DiscussionsApi(self::$client,env('CDISCOUNT_API_URL'), env('CDISCOUNT_SELLERID'));
            $this->logger->info('--- Get all discussions ---');
            $listDiscussionId = $discussion->getAllDiscussions($from_date);

            $this->logger->info('--- Get details of discussion ---');
            $allDiscussion = $discussion->getAllDiscussionDetails($listDiscussionId);
        } catch (Exception $e) {
            $this->logger->error('An error has occurred on API call', $e);
            \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
        }
        foreach ($allDiscussion as $discu){
            try {

                $this->logger->info('--- check chat modification time ---');
                if ($discu->getUpdatedAt()->getTimestamp() > $from_time) {
                    DB::beginTransaction();
                    $this->logger->info('Begin Transaction');

                    $orderReference = $discu->getOrderReference();
                    $order = Order::getOrder($orderReference, $this->channel);
                    $ticket = Ticket::getTicket($order, $this->channel);

                    $this->logger->info('Message recovery');
                    $messages = $discu->getMessages();
                    $channel_data = [
                        "salesChannelExternalReference" => $discu->getSalesChannelExternalReference(),
                        "salesChannel" => $discu->getSalesChannel(),
                        "userId" => $discu->getCustomerId(),
                    ];
                    $thread = Thread::getOrCreateThread($ticket, $discu->getDiscussionId(), $discu->getSubject(), '', $channel_data);

                    $this->importMessageByThread($ticket, $thread, $messages);
                    DB::commit();
                }
            } catch (Exception $e){
                $this->logger->error('An error has occurred. Rolling back.', $e);
                DB::rollBack();
                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
        }
    }
    protected function getCredentials(): array
    {
        return [
            'API_USERNAME'  => env('CDISCOUNT_USERNAME'),
            'API_KEY'       => env('CDISCOUNT_PASSWORD'),
        ];
    }

    protected function initApiClient(){
        $credentials = $this->getCredentials();
        self::$client = new ClientCdiscount(
            $credentials['API_USERNAME'],
            $credentials['API_KEY']
        );
    }

    /**
     * @param Ticket $ticket
     * @param Thread $thread
     * @param array $messages
     * @throws Exception
     */
    private function importMessageByThread(Ticket $ticket, Thread $thread,array $messages)
    {
        foreach ($messages as $message) {
            $imported_id = $message->getMessageId();
            $authorType = $message->getSender()->getUserType();
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
     * @param $message_api
     * @param Thread $thread
     */
    public function convertApiResponseToMessage(Ticket $ticket, $message_api, Thread $thread)
    {
        $authorType = $message_api->getSender()->getUserType();
        $isNotShopUser = self::isNotShopUser($authorType, $this->FROM_SHOP_TYPE);
        if ($isNotShopUser) {
            $this->logger->info('Set ticket\'s status to waiting admin');
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            Message::firstOrCreate([
                'thread_id' => $thread->id,
                'channel_message_number' => $message_api->getMessageId(),
            ],
                [
                    'user_id' => null,
                    'author_type' => self::getAuthorType($authorType),
                    'content' => strip_tags($message_api->getBody()),
                ],
            );
        }
        if (setting('autoReplyActivate') && $ticket->order->channel_order_number == '2302201135UQL01') {
            $this->logger->info('Send auto reply');
            self::sendAutoReply(setting('autoReply'), $thread);
        }
    }

    /**
     * @throws Exception
     */
    protected function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'Customer' => TicketMessageAuthorTypeEnum::CUSTOMER, //
            'GrcOperator' => TicketMessageAuthorTypeEnum::OPERATOR, //
            default => throw new Exception('Bad author type.')
        };
    }
}
