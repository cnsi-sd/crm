<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class CarrefourImportMessage extends MiraklImportMessage
{
    protected $signature = 'carrefour:import:messages';
    protected $description = 'import Carrefour message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('CARREFOUR_API_URL'),
            env('CARREFOUR_API_KEY'),
            env('CARREFOUR_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
