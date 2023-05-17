<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class Exception extends AbstractMail implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        protected \Exception $exception,
        protected            $code,
        protected            $commandName,
        protected            $commandDescription
    )
    {
    }

    public function build(): self
    {
        $this
            ->subject(date(' [d/m H:i] ') . __('app.mail.process_error') . ' - ' . $this->commandName)
            ->markdown('emails.notifications.jobs.exception')
            ->with([
                'exception' => $this->exception,
                'commandName' => $this->commandName,
                'code' => $this->code,
                'commandDescription' => $this->commandDescription
            ]);
        return $this;
    }

    public static function sendErrorMail($e, $commandName, $commandDescription, $output)
    {
        $recipients = explode(',', env('ERROR_RECIPIENTS'));
        $code = $e->getCode();
        $mail       = new Exception($e, $code, $commandName, $commandDescription);
        Mail::to($recipients)->send($mail);
        app(ExceptionHandler::class)->renderForConsole($output, $e);
        app(ExceptionHandler::class)->report($e);
    }
}
