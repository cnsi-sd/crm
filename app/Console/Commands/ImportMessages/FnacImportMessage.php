<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class FnacImportMessage extends MiraklImportMessage
{
    protected $signature = 'fnac:import:messages';
    protected $description = 'import Fnac message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('FNAC_API_URL'),
            env('FNAC_API_KEY'),
            env('FNAC_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
