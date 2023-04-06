<?php

namespace App\Jobs\Bot\Answers;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;

abstract class AbstractAnswer
{
    protected Message $message;

    const SKIP = false;
    const STOP_PROPAGATION = true;

    public function __construct(Message $message) {
        $this->message = $message;
    }

    /**
     * This method should return if the message has been answered or not
     * @return bool
     */
    abstract public function handle(): bool;

    protected function addDefaultAnswerToThread(DefaultAnswer $defaultAnswer): Message
    {
        $message = new Message();
        $message->thread_id = $this->message->thread_id;
        $message->user_id = null;
        $message->channel_message_number = '';
        $message->author_type = TicketMessageAuthorTypeEnum::SYSTEM;
        $message->content = $defaultAnswer->content;
        $message->save();
        return $message;
    }
}
