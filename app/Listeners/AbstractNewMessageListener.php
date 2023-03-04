<?php

namespace App\Listeners;

use App\Events\NewMessage;
use App\Models\Ticket\Message;

abstract class AbstractNewMessageListener
{
    protected NewMessage $event;
    protected Message $message;

    const SKIP = null;
    const STOP_PROPAGATION = false;

    abstract public function handle(NewMessage $event): ?bool;
}
