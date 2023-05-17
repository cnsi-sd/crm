<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\Channel;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;
use App\Models\Ticket\Thread;
use App\Models\Ticket\Ticket;
use Cnsi\Logger\Logger;
use DateTime;
use Exception;
use Illuminate\Console\Command;

abstract class AbstractImportMessages extends Command
{
    protected Logger $logger;
    protected Channel $channel;
    protected static mixed $_alreadyImportedMessages = false;

    protected $signature = '%s:import:messages';
    protected $description = 'Importing messages from Marketplace.';

    abstract protected function getCredentials(): array;
    abstract protected function initApiClient();
    abstract protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread, $attachments = []);

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

    protected function checkMessageDate(DateTime $messageDate): bool
    {
        $startDateFormat = "Y-m-d H:i:s";
        $starter_date = DateTime::createFromFormat($startDateFormat, env('STARTER_DATE_CRM'));
        if(!$starter_date)
            throw new Exception('The environment variable isn\'t in the correct format or does not exist (expected format : ' . $startDateFormat . ')');

        return $starter_date < $messageDate;
    }
}

