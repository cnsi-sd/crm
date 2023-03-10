<?php

namespace App\Jobs\SendMail;

use App\Jobs\SendMessage\AbstractSendMessage;
use Illuminate\Support\Facades\Mail;

class AmazonSendMessage extends AbstractSendMessage
{
    protected function sendMessage(): void
    {
        $data["email"] = json_decode($this->message->thread->channel_data)['replyTo'];
        $data["title"] = "Re :" . $this->message->thread->name;
        $data["body"] = $this->message->content;
        Mail::send('emails.ticketDelay', $data, function ($message) use ($data) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"]);
        });
    }
}
