<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class RueDuCommerceImportMessage extends MiraklImportMessage
{
    protected $signature = 'rueducommerce:import:messages';
    protected $description = 'import Rue du commerce message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('RUEDUCOMMERCE_API_URL'),
            env('RUEDUCOMMERCE_API_KEY'),
            env('RUEDUCOMMERCE_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
