<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class ButImportMessage extends MiraklImportMessage
{
    protected $signature = 'but:import:messages';
    protected $description = 'import But message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('BUT_API_URL'),
            env('BUT_API_KEY'),
            env('BUT_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
