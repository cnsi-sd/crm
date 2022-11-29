<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

abstract class AbstractMail extends Mailable
{
    public function subject($subject)
    {
        $this->subject = "[CRM] " . $subject;
        return $this;
    }
}
