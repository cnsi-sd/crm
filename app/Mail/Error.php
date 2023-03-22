<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class Error extends AbstractMail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        protected $message,
        protected $commandName,
        protected $commandDescription
    )
    {
    }

    public function build(): self
    {
        $this
            ->subject(date(' [d/m H:i] ') . __('app.mail.process_error') . ' - ' . $this->commandName)
            ->markdown('emails.notifications.jobs.error')
            ->with([
                'message' => $this->message,
                'commandName' => $this->commandName,
                'commandDescription' => $this->commandDescription
            ]);
        return $this;
    }

    /**
     * @param array|string $messages
     * @param $commandName
     * @param $commandDescription
     * @return void
     */
    public static function sendErrorMail(array|string $messages, $commandName, $commandDescription): void
    {
        $messages = Arr::wrap($messages);
        $messages = implode("<br>", $messages);

        $recipients = explode(',', env('ERROR_RECIPIENTS'));
        $mail = new Error($messages, $commandName, $commandDescription);
        Mail::to($recipients)->send($mail);
    }
}
