<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use Cnsi\Logger\Logger;
use Exception;
use Illuminate\Support\Facades\Mail;


class ManomanoSendMessage extends AbstractSendMessage
{
    protected Logger $logger;
    /**
     * @throws Exception
     */
    public function handle(): void
    {
        // Load channel
        $this->channel = Channel::getByName(ChannelEnum::MANOMANO_COM);
        $this->logger = new Logger('send_message/'
            . $this->channel->getSnakeName()
            . '/' . $this->channel->getSnakeName()
            . '.log', true, true
        );

        $this->logger->info('--- Start ---');

        $toMailAddress = str_replace('"',"", $this->message->thread->channel_data);

        $response = imap_mail(
            $toMailAddress,
            $this->message->thread->name,
            $this->message->content,'','','',
            'laurent.lefoulgoc@cnsi-sd.fr');



        Mail::raw($toMailAddress)->send();

        $response
            ? $this->logger->info('Message sent to '. $toMailAddress)
            : $this->logger->info('An error occurred, mail to ' . $toMailAddress . ' sending problem');
    }

    public function buildMail()
    {

    }
}
