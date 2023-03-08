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
use Exception;
use Illuminate\Console\Command;

abstract class AbstractImportMessages extends Command
{
    protected Logger $logger;
    protected Channel $channel;
    protected static mixed $_alreadyImportedMessages = false;
    protected string $testOrder; // add a test order for each mp to avoid to send messages randomly

    protected $signature = '%s:import:messages';
    protected $description = 'Importing messages from Marketplace.';

    abstract protected function getCredentials(): array;
    abstract protected function initApiClient();
    abstract protected function convertApiResponseToMessage(Ticket $ticket, $message_api_api, Thread $thread);

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
     * @param Thread $thread
     * @return void
     * @throws Exception
     */
    public function sendAutoReply(Thread $thread): void
    {
        if(!setting('bot.acknowledgment.active')) {
            return;
        }

        if(env('APP_ENV') == 'local')
            if($thread->ticket->order->channel_order_number != $this->testOrder)
                return;

        $this->logger->info('Send auto reply');
        $autoReplyId = setting('bot.acknowledgment.answer_id');
        $autoReplyContentWeek = DefaultAnswer::query()->select('content')->where('id', $autoReplyId)->first();

        $autoReply = new Message();
        $autoReply->thread_id = $thread->id;
        $autoReply->user_id = null;
        $autoReply->channel_message_number = '';
        $autoReply->author_type = TicketMessageAuthorTypeEnum::ADMIN;
        $autoReply->content = $autoReplyContentWeek['content'];
        $autoReply->save();

        AbstractSendMessage::dispatchMessage($autoReply);
    }
}

