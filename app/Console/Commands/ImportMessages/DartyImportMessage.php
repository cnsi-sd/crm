<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class DartyImportMessage extends MiraklImportMessage
{
    protected $signature = 'darty:import:messages';
    protected $description = 'import Darty message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('DARTY_API_URL'),
            env('DARTY_API_KEY'),
            env('DARTY_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
