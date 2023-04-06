<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Enums\Ticket\MessageVariable;
use App\Models\Channel\Channel;
use Illuminate\Support\Facades\Mail;

class EmailSendMessage extends AbstractSendMessage
{
    protected function sendMessage(): void
    {
        // If we are not in production environment, we don't want to send mail
        if (env('APP_ENV') != 'production')
            return;

        $this->channel = Channel::getByName(ChannelEnum::AMAZON_FR);

        $email = str_replace('"', "", $this->message->thread->channel_data);
        $body = $this->message->content;

        $title = "Nouveau message au sujet de votre commande";
        if ($shopName = MessageVariable::NOM_BOUTIQUE->getSettingValue()) {
            $title .= " " . $shopName;
        }

        Mail::raw($body, function ($message) use ($title, $email) {
            $message->to($email)
                ->subject($title);
        });
    }
}
