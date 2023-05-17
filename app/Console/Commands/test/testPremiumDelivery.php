<?php

namespace App\Console\Commands\test;

use App\Jobs\Bot\AnswerToNewMessage;
use App\Models\Ticket\Message;
use Illuminate\Console\Command;

class testPremiumDelivery extends Command
{
    protected $signature = 'delivery:premium';
    protected $description = 'Test premium delivery.';

    public function handle(): void
    {
        $message = Message::find(331);
        AnswerToNewMessage::dispatch($message);
    }
}
