<?php

namespace App\Jobs\SendMail;

use App\Models\Channel\Channel;
use App\Models\Ticket\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AmazonSendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Message $message;
    protected Channel $channel;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function handle(){
        $data["email"] = json_decode($this->message->thread->channel_data)['replyTo'];
        $data["title"] = "Re :" . $this->message->thread->name;
        $data["body"] = $this->message->content;
        Mail::send('emails.ticketDelay', $data, function($message)use($data) {
            $message->to($data["email"], $data["email"])
                ->subject($data["title"]);
        });
    }
}
