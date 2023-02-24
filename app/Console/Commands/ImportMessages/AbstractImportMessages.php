<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Jobs\SendMessage\ButSendMessage;
use App\Jobs\SendMessage\CarrefourSendMessage;
use App\Jobs\SendMessage\CdiscountSendMessage;
use App\Jobs\SendMessage\ConforamaSendMessage;
use App\Jobs\SendMessage\DartySendMessage;
use App\Jobs\SendMessage\FnacSendMessage;
use App\Jobs\SendMessage\IcozaSendMessage;
use App\Jobs\SendMessage\IntermarcheSendMessage;
use App\Jobs\SendMessage\LaposteSendMessage;
use App\Jobs\SendMessage\LeclercSendMessage;
use App\Jobs\SendMessage\MetroSendMessage;
use App\Jobs\SendMessage\RueDuCommerceSendMessage;
use App\Jobs\SendMessage\ShowroomSendMessage;
use App\Jobs\SendMessage\UbaldiSendMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Console\Command;

abstract class AbstractImportMessages extends Command
{
    protected Logger $logger;
    protected Channel $channel;
    protected string $log_path;
    protected static mixed $_alreadyImportedMessages = false;

    protected $signature = '%s:import:messages {--S|sync} {--T|thread=} {--only_best_prices} {--only_updated_offers} {--exclude_supplier=*} {--only_best_sellers} {--part=}';
    protected $description = 'Importing messages from Marketplace.';

    abstract protected function getCredentials(): array;

    abstract protected function initApiClient();

    abstract protected function getAuthorType(string $authorType): string;
    abstract protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread);

    /**
     * returns if the message type is SHOP_USER
     * @param string $type
     * @param $FROM_SHOP_TYPE
     * @return bool
     */
    protected static function isNotShopUser(string $type, $FROM_SHOP_TYPE): bool
    {
        return $FROM_SHOP_TYPE !== $type;
    }

    /**
     * @throws Exception
     */
    protected function isMessagesImported(string $channel_message_number): bool
    {
        if (!static::$_alreadyImportedMessages) {
            static::$_alreadyImportedMessages = \App\Models\Ticket\Message::query()
                ->select('channel_message_number')
                ->join('ticket_threads', 'ticket_threads.id', '=', 'ticket_thread_messages.thread_id') // thread
                ->join('tickets', 'tickets.id', '=', 'ticket_threads.ticket_id') // ticket
                ->where('channel_id', $this->channel->id)
                ->get()
                ->pluck('channel_message_number', 'channel_message_number')
                ->toArray();
        }

        return isset(static::$_alreadyImportedMessages[$channel_message_number]);
    }

    protected function addImportedMessageChannelNumber(string $channel_message_number): void
    {
        static::$_alreadyImportedMessages[$channel_message_number] = $channel_message_number;
    }

    /**
     * @param mixed $messageId
     * @param Thread $thread
     * @return void
     */
    public function sendAutoReply(mixed $messageId, Thread $thread): void
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
            ChannelEnum::CDISCOUNT_FR => CdiscountSendMessage::dispatch($autoReply),
            ChannelEnum::FNAC_COM => FnacSendMessage::dispatch($autoReply),
            ChannelEnum::ICOZA_FR => IcozaSendMessage::dispatch($autoReply),
        };
    }
}

