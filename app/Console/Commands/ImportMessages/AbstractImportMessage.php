<?php

namespace App\Console\Commands\ImportMessages;

use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use FnacApiClient\Entity\Message;
use Illuminate\Console\Command;
use Mirakl\MMP\Common\Domain\Message\Thread\ThreadMessage;

abstract class AbstractImportMessage extends Command
{
    protected Logger $logger;
    protected string $log_path;
    protected static mixed $_alreadyImportedMessages = false;

    protected $signature = '%s:import:messages {--S|sync} {--T|thread=} {--only_best_prices} {--only_updated_offers} {--exclude_supplier=*} {--only_best_sellers} {--part=}';
    protected $description = 'Importing messages from Marketplace.';

    abstract protected function getChannelName(): string;

    abstract protected function getCredentials(): array;

    abstract protected function getMessageApiId(ThreadMessage | Message $message): string;

    abstract protected function getMpOrderApiId($message, $thread = null);

    abstract protected function convertApiResponseToMessage(Ticket $ticket, $message, Thread $thread);

    const FROM_SHOP_TYPE = [
        'SHOP_USER',
        'CALLCENTER',
        'SELLER'
    ];

    /**
     * returns if the message type is SHOP_USER
     * @param string $type
     * @return bool
     */
    private static function isNotShopUser(string $type): bool
    {
        return !in_array($type, self::FROM_SHOP_TYPE);
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
                ->where('channel_id', Channel::getByName($this->getChannelName())->id) //TODO get real name
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
}

