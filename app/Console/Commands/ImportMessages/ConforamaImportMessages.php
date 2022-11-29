<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

class ConforamaImportMessages extends AbstractMiraklImportMessage
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'conforama');
        return parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::CONFORAMA_FR;
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('CONFORAMA_API_URL'),
            'API_KEY' => env('CONFORAMA_API_KEY'),
            'API_SHOP_ID' => env('CONFORAMA_API_SHOP_ID'),
        ];
    }

}
