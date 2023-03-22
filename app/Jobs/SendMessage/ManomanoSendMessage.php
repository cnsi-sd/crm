<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use Cnsi\Logger\Logger;
use Exception;
use http\Message;
use Illuminate\Support\Facades\Mail;


class ManomanoSendMessage extends AbstractSendMessage
{
    protected Logger $logger;
    /**
     * @throws Exception
     */
    public function sendMessage(): void
    {
        // If we are not in production environment, we don't want to send mail
        if (env('APP_ENV') != 'production')
            return;

        // Load channel
        $this->channel = Channel::getByName(ChannelEnum::MANOMANO_COM);
        $this->logger = new Logger('send_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');
        $toMailAddress = str_replace('"',"", $this->message->thread->channel_data);
        $this->logger->info('Message sending to '. $toMailAddress);

        $response = Mail::raw($this->message->content, function ($message) use ($toMailAddress) {
         $message->to($toMailAddress)
         ->subject('RE: '. $this->message->thread->name);
        });

        $response
            ? $this->logger->info('Message sent to '. $toMailAddress)
            : $this->logger->info('An error occurred, mail to ' . $toMailAddress . ' sending problem');
    }
}
