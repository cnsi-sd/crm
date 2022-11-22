<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class MetroImportMessage extends MiraklImportMessage
{
    protected $signature = 'metro:import:messages';
    protected $description = 'import Metro message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('METRO_API_URL'),
            env('METRO_API_KEY'),
            env('METRO_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
