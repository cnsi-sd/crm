<?php

namespace App\Listeners;

use App\Events\NewMessage;

class SendAcknowledgement extends AbstractListener
{
    public function handle(NewMessage $event)
    {
        $message = $event->getMessage();

        if(!$message->isExternal())
            return self::SKIP;

        // TODO : send acknowledgement

        return self::STOP_PROPAGATION;
    }
}
