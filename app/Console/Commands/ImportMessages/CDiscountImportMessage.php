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
use App\Jobs\SendMessage\RueDuCommerceSendMessage;
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

class CDiscountImportMessage extends Command
{
    protected Logger $logger;

    protected $signature = 'cdiscount:import:messages';
    protected $description = 'Command description';

    protected string $channel_name = ChannelEnum::CDISCOUNT_FR;

    /**
     * @var ClientCdiscount
     */
    static private $client = null;

    const FROM_DATE_TRANSFORMATOR = ' - 2 hours';
    const MAX_RETRY_API_CALL = 5;
    const IGNORE_MSG_CONTAINS = [
        '----- The following addresses had permanent fatal errors -----',
        'THIS IS A WARNING MESSAGE ONLY',
        'THIS IS A WARNING ONLY.',
        'Votre demande d’annulation a été acceptée. Le remboursement est en cours.',
    ];

    protected static $_alreadyImportedMessages;

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle()
    {
        $this->logger = new Logger('import_message/' . $this->channel_name . '/' . $this->channel_name . '.log', true, true);
        $this->logger->info('--- Start ---');
        try {
            $from_time = strtotime(date('Y-m-d H:m:s') . self::FROM_DATE_TRANSFORMATOR);
            $from_date = date('Y-m-d H:m:i', $from_time);

            $discussion = new DiscussionsApi(env('CDISCOUNT_USERNAME'), env('CDISCOUNT_PASSWORD'), env('CDISCOUNT_SELLERID'));

            $this->logger->info('--- Get all discussion ---');
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
                    $channel = Channel::getByName($this->channel_name);
                    $order = Order::getOrder($orderReference, $channel);
                    $ticket = Ticket::getTicket($order, $channel);

                    $messages = $discu->getMessages();
                    $channel_data = [
                        "salesChannelExternalReference" => $discu->getSalesChannelExternalReference(),
                        "salesChannel" => $discu->getSalesChannel(),
                        "userId" => $discu->getCustomerId(),
                    ];
                    //$this->logger->info(json_encode($channel_data));
                    $thread = Thread::getOrCreateThread($ticket, $discu->getDiscussionId(), $discu->getSubject(), '', $channel_data);

                    $this->importMessageByThread($ticket, $thread, $messages);
                    DB::commit();
                    break;
                }
            } catch (Exception $e){
                $this->logger->error('An error has occurred. Rolling back.', $e);
                DB::rollBack();
                \App\Mail\Exception::sendErrorMail($e, $this->getName(), $this->description, $this->output);
                return;
            }
        }
    }

    const FROM_SHOP_TYPE = 'Seller';

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
     * @param Thread $thread
     * @param array $messages
     * @throws Exception
     */
    private function importMessageByThread(Ticket $ticket, Thread $thread,array $messages)
    {
        foreach ($messages as $message) {
            $imported_id = $message->getMessageId();
            if (!$this->isMessagesImported($imported_id)) {
                $this->convertApiResponseToMessage($ticket, $message, $thread);
                $this->addImportedMessageChannelNumber($imported_id);
            }
        }
    }

    /**
     * Convert api messages into message model in order to save it in database
     * @param Ticket $ticket
     * @param \Cnsi\Cdiscount\Discussion\Message $api_message
     * @param Thread $thread
     */
    public function convertApiResponseToMessage(Ticket $ticket, \Cnsi\Cdiscount\Discussion\Message $api_message, Thread $thread)
    {
        $authorType = $api_message->getSender()->getUserType();
        $isNotShopUser = self::isNotShopUser($authorType);
        if ($isNotShopUser) {
            $ticket->state = TicketStateEnum::WAITING_ADMIN;
            $ticket->save();
            Message::firstOrCreate([
                'thread_id' => $thread->id,
                'channel_message_number' => $api_message->getMessageId(),
            ],
                [
                    'thread_id' => $thread->id,
                    'user_id' => null,
                    'channel_message_number' => $api_message->getMessageId(),
                    'author_type' => self::getAuthorType($authorType),
                    'content' => strip_tags($api_message->getBody()),
                ],
            );
        }
        if (setting('autoReplyActivate')) {
            self::sendAutoReply(setting('autoReply'), $thread);
        }
    }

    private static function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'Customer' => TicketMessageAuthorTypeEnum::CUSTOMER,
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
                ->where('channel_id', Channel::getByName($this->channel_name)->id)
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

        match ($thread->ticket->channel->name) {
            ChannelEnum::BUT_FR => ButSendMessage::dispatch($autoReply),
            ChannelEnum::CARREFOUR_FR => CarrefourSendMessage::dispatch($autoReply),
            ChannelEnum::CONFORAMA_FR => ConforamaSendMessage::dispatch($autoReply),
            ChannelEnum::DARTY_COM => DartySendMessage::dispatch($autoReply),
            ChannelEnum::INTERMARCHE_FR => IntermarcheSendMessage::dispatch($autoReply),
            ChannelEnum::LAPOSTE_FR => LaposteSendMessage::dispatch($autoReply),
            ChannelEnum::E_LECLERC => LeclercSendMessage::dispatch($autoReply),
            ChannelEnum::METRO_FR => MetroSendMessage::dispatch($autoReply),
            ChannelEnum::RUEDUCOMMERCE_FR => RueDuCommerceSendMessage::dispatch($autoReply),
            ChannelEnum::SHOWROOMPRIVE_COM => ShowroomSendMessage::dispatch($autoReply),
            ChannelEnum::UBALDI_COM => UbaldiSendMessage::dispatch($autoReply),
            ChannelEnum::CDISCOUNT_FR => CdiscountSendMessage::dispatch($autoReply)
        };
    }
}
