<?php

namespace App\Jobs\SendMessage;

use App\Enums\Ticket\MessageVariable;
use App\Mail\RawMail;
use App\Models\Ticket\Thread;
use Illuminate\Support\Facades\Mail;

class EmailSendMessage extends AbstractSendMessage
{
    protected function sendMessage(): void
    {
        // If we are not in production environment, we don't want to send mail
        if (env('APP_ENV') != 'production')
            return;

        $to = str_replace('"', "", $this->message->thread->channel_data);

        $this->attachments = $this->message->documents()->get();

        if ($this->message->thread->name === Thread::EMAIL) {
            $subject = "Nouveau message au sujet de votre commande";
            if ($shopName = MessageVariable::NOM_BOUTIQUE->getSettingValue()) {
                $subject .= ' ' . $shopName;
            }
        } else {
            $subject = 'RE: ' . $this->message->thread->name;
        }

        $mail = new RawMail($subject, $this->message->content);
        if($this->attachments->count() > 0) {
            foreach ($this->attachments as $attachment) {
                $mail->attach($attachment->getFilePath());
            }
        }
        Mail::to($to)->send($mail);
    }
}
