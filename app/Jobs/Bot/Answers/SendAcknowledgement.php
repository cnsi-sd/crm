<?php

namespace App\Jobs\Bot\Answers;

use App\Enums\Ticket\TicketMessageAuthorTypeEnum;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\DefaultAnswer;
use App\Models\Ticket\Message;

class SendAcknowledgement extends AbstractAnswer
{
    private DefaultAnswer $answerAcknowledgement;

    public function handle(): bool
    {
        $this->answerAcknowledgement = DefaultAnswer::findOrFail(setting('bot.acknowledgment.answer_id'));

        if(!$this->canBeProcessed())
            return self::SKIP;

        $this->sendAcknowledgementAnswer();

        return self::STOP_PROPAGATION;
    }

    protected function canBeProcessed(): bool
    {
        if(!setting('bot.acknowledgment.active'))
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
