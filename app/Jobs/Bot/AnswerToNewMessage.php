<?php

namespace App\Jobs\Bot;

use App\Jobs\Bot\Answers\AbstractAnswer;
use App\Jobs\Bot\Answers\SendAcknowledgement;
use App\Jobs\Bot\Answers\SendInvoice;
use App\Jobs\Bot\Answers\SendShippingInformation;
use App\Models\Ticket\Message;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AnswerToNewMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    use Dispatchable {
        dispatch as protected originalDispatch;
    }

    private Message $message;

    const DELAY = 5;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    // Override the default original method to add a delay
    public static function dispatch(...$arguments): PendingDispatch
    {
        $delay = now()->addMinutes(self::DELAY);
        return self::originalDispatch(...$arguments)->delay($delay);
    }

    public function handle()
    {
        // List of Answer class
        // Be carefully, order has an importance.
        // SendAcknowledgement must always be the last.
        $answerClasses = [
            SendInvoice::class,
            SendShippingInformation::class,
            SendAcknowledgement::class,
        ];

        // Loop on each Answer class
        // Classes will try to answer the message according their rules
        // We stop as soon as a class has responded
        foreach ($answerClasses as $answerClass) {
            if(!is_subclass_of($answerClass, AbstractAnswer::class))
                throw new Exception('Answer `' . $answerClass . '` must implement the AbstractAnswer class');

            $answer = new $answerClass($this->message);
            $result = $answer->handle();

            if($result === AbstractAnswer::STOP_PROPAGATION)
                break;
        }
    }
}
