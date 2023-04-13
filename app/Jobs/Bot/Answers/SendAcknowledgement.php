<?php

namespace App\Jobs\Bot\Answers;

use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\DefaultAnswer;

class SendAcknowledgement extends AbstractAnswer
{
    public function handle(): bool
    {
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

        if($this->message->hasBeenAnswered())
            return false;

        return true;
    }

    private function sendAcknowledgementAnswer()
    {
        $defaultAnswer = DefaultAnswer::findOrFail(setting('bot.acknowledgment.answer_id'));
        $message = $this->addDefaultAnswerToThread($defaultAnswer);
        AbstractSendMessage::dispatchMessage($message);
    }
}
