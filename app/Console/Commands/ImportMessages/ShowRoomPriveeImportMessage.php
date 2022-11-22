<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class ShowRoomPriveeImportMessage extends MiraklImportMessage
{
    protected $signature = 'showroom:import:messages';
    protected $description = 'import ShowRoom privée message';

    protected $channelId = 1;


    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('SHOWROOM_API_URL'),
            env('SHOWROOM_API_KEY'),
            env('SHOWROOM_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }


}
