<?php

namespace App\Listeners;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Events\NewMessage;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;

class SendAcknowledgement extends AbstractNewMessageListener
{
    private DefaultAnswer $answerAcknowledgement;

    public function handle(NewMessage $event): ?bool
    {
        $this->event = $event;
        $this->message = $event->getMessage();
        $this->answerAcknowledgement = DefaultAnswer::findOrFail(setting('autoReply'));

        if(!$this->canBeProcessed())
            return self::SKIP;

        $this->sendAcknowledgementAnswer();
        $this->message->thread->ticket->close();

        return self::STOP_PROPAGATION;
    }

    protected function canBeProcessed(): bool
    {
        if(!setting('autoReplyActivate'))
            return false;

        if(!$this->message->isExternal())
            return false;

        return true;
    }

    private function sendAcknowledgementAnswer()
    {
        // Build message
        $answer = new Message();
        $answer->thread_id = $this->message->thread_id;
        $answer->user_id = null;
        $answer->channel_message_number = '';
        $answer->author_type = TicketMessageAuthorTypeEnum::SYSTEM;
        $answer->content = $this->answerAcknowledgement->content;
        $answer->save();

        // Send message
        AbstractSendMessage::dispatchMessage($answer);
    }
}
