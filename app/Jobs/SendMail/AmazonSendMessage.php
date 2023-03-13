<?php

namespace App\Jobs\SendMail;

use App\Enums\Channel\ChannelEnum;
use App\Jobs\SendMessage\AbstractSendMessage;
use App\Models\Channel\Channel;
use Cnsi\Logger\Logger;
use Illuminate\Support\Facades\Mail;

class AmazonSendMessage extends AbstractSendMessage
{
    protected Logger $logger;

    /**
     * @throws \Exception
     */
    protected function sendMessage(): void
    {
        $this->channel = Channel::getByName(ChannelEnum::AMAZON_FR);
        $this->logger = new Logger('send_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $data["email"] = str_replace('"',"", $this->message->thread->channel_data);
        $data["title"] = "RE: " . $this->message->thread->name;
        $data["body"] = $this->message->content;

        Mail::raw( $data["body"], function ($message) use ($data) {
            $message->to($data["email"])
                ->subject($data["title"]);
        });
    }
}
