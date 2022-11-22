<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class Leclerc extends MiraklImportMessage
{
    protected $signature = 'leclerc:import:messages';
    protected $description = 'import leclerc message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('LECLERC_API_URL'),
            env('LECLERC_API_KEY'),
            env('LECLERC_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
