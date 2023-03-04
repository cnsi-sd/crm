<?php

namespace App\Listeners;

use App\Events\NewMessage;

class SendAcknowledgement extends AbstractNewMessageListener
{
    public function handle(NewMessage $event): ?bool
    {
        $this->event = $event;
        $this->message = $event->getMessage();

        if(!$this->canBeProcessed())
            return self::SKIP;

        // TODO : send acknowledgement

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
}
