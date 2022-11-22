<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class ConforamaImportMessage extends MiraklImportMessage
{
    protected $signature = 'conforama:import:messages';
    protected $description = 'import Conforama message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('CONFORAMA_API_URL'),
            env('CONFORAMA_API_KEY'),
            env('CONFORAMA_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
