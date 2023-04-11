<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RawMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public string $content;

    public function __construct($subject, $content)
    {
        $this->content = $content;
        $this->subject = $subject;
    }

    public function build()
    {
        $this->view('emails.raw');
        $this->subject($this->subject);
    }
}
