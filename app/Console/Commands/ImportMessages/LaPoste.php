<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class LaPoste extends MiraklImportMessage
{
    protected $signature = 'laposte:import:messages';
    protected $description = 'import La poste message';

    protected $channelId = 2;


    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('LAPOSTE_API_URL'),
            env('LAPOSTE_API_KEY'),
            env('LAPOSTE_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }


}
