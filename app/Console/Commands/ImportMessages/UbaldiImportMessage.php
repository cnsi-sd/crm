<?php

namespace App\Console\Commands\ImportMessages;

use Mirakl\MMP\Shop\Client\ShopApiClient;

class   UbaldiImportMessage extends MiraklImportMessage
{
    protected $signature = 'ubaldi:import:messages';
    protected $description = 'import Ubaldi message';

    protected $channelId = 3;

    protected function initApiClient(): ShopApiClient
    {
        $this->client = new ShopApiClient(
            env('UBALDI_API_URL'),
            env('UBALDI_API_KEY'),
            env('UBALDI_API_SHOP_ID'),
        );
        $this->client->addOption('connect_timeout', self::HTTP_CONNECT_TIMEOUT);
        return $this->client;
    }
}
