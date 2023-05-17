<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;

class BoulangerImportMessages extends AbstractMiraklImportMessages
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'boulanger');
        parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::BOULANGER_COM;
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('BOULANGER_API_URL'),
            'API_KEY' => env('BOULANGER_API_KEY'),
            'API_SHOP_ID' => env('BOULANGER_API_SHOP_ID'),
        ];
    }
}
