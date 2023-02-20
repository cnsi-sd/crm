<?php

namespace App\Jobs\SendMessage;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;
use Cnsi\Logger\Logger;
use Exception;
use FnacApiClient\Client\SimpleClient;
use FnacApiClient\Exception\ErrorResponseException;
use FnacApiClient\Service\Request\MessageUpdate;
use FnacApiClient\Type\MessageToType;

class FnacSendMessage extends AbstractSendMessage
{
    protected Logger $logger;
    static private ?SimpleClient $client = null;

    /**
     * @throws Exception
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::FNAC_COM;
    }

    /**
     * @throws Exception
     */
    protected function getSnakeChannelName(): array|string
    {
        return (new Channel)->getSnakeName($this->getChannelName());
    }

    /**
     * @throws ErrorResponseException
     */
    public function handle(): void
    {
        // Variables
        $sendTo = MessageToType::CLIENT;
        $thread_id = $this->message->thread->channel_thread_number;

        // Init API client
        $client = $this->initApiClient();

        $query = new MessageUpdate();


    }

    protected function getCredentials(): array
    {
        return [
            'host'       => env('FNAC_API_URL'),
            'shop_id'    => env('FNAC_API_SHOP_ID'),
            'key'        => env('FNAC_API_KEY'),
            'partner_id' => env('FNAC_API_PARTNER_ID'),
        ];
    }

    /**
     * @throws ErrorResponseException
     * @throws Exception
     */
    protected function initApiCLient(): ?SimpleClient
    {
        if(self::$client == null) {
            $client = new SimpleClient();

            $this->logger = new Logger('send_message/' . $this->getSnakeChannelName() . '/' . $this->getSnakeChannelName() . '.log', true, true);
            $client->init(self::getCredentials());
            $client->checkAuth();

            self::$client = $client;
        }

        return self::$client;
    }
}
