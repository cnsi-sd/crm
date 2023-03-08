<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use Cnsi\Logger\Logger;
use Exception;
use Webklex\PHPIMAP\Client;

class ManomanoSendMessage extends AbstractSendMessage
{
    protected Logger $logger;
    private Client $client;
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
    }
}
