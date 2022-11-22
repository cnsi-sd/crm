<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class IntermarcheImportMessage extends MiraklImportMessage
{
    protected $signature = 'intermarche:import:messages';
    protected $description = 'import Intermarche message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('INTERMARCHE_API_URL'),
            env('INTERMARCHE_API_KEY'),
            env('INTERMARCHE_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
