<?php

namespace App\Jobs\Bot\Answers;

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
}
