<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Models\Channel\Channel;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Console\Command;

abstract class AbstractImportMessage extends Command
{
    protected Logger $logger;
    protected string $log_path;
    protected static mixed $_alreadyImportedMessages = false;

    protected $signature = '%s:import:messages {--S|sync} {--T|thread=} {--only_best_prices} {--only_updated_offers} {--exclude_supplier=*} {--only_best_sellers} {--part=}';
    protected $description = 'Importing messages from Marketplace.';

    abstract protected function getChannelName(): string;

    abstract protected function getCredentials(): array;

    abstract protected function getMessageApiId($message): string;

    abstract protected function getMpOrderApiId($message, $thread = null);

    abstract protected function convertApiResponseToMessage(Ticket $ticket, $message, Thread $thread);

    const FROM_SHOP_TYPE = [
        'SHOP_USER',
        'CALLCENTER',
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
    protected function importMessageByThread(Ticket $ticket, \App\Models\Ticket\Thread $thread, $messages)
    {
        foreach ($messages as $message) {
            $this->logger->info('Check if this message is imported');
            $imported_id = $message->getMessageApiId();
            if (!$this->isMessagesImported($imported_id)) {
                $this->logger->info('Convert api message to db message');
                $this->convertApiResponseToMessage($ticket, $message, $thread);
                $this->addImportedMessageChannelNumber($imported_id);
            }
        }
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
//                ->where('channel_id', Channel::getByName(ChannelEnum::FNAC_COM)) //TODO get real name
                ->where('channel_id', Channel::getByName($this->getChannelName())) //TODO get real name
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

    private static function getAuthorType(string $authorType): string
    {
        return match ($authorType) {
            'CUSTOMER_USER' => TicketMessageAuthorTypeEnum::CUSTOMER,
            'CLIENT' => TicketMessageAuthorTypeEnum::CLIENT,
            'CALLCENTER' => TicketMessageAuthorTypeEnum::CALLCENTER,
            default => TicketMessageAuthorTypeEnum::OPERATEUR,
        };
    }
}

