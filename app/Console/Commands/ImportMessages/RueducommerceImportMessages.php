<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;

class RueducommerceImportMessages extends AbstractMiraklImportMessages
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'rueducommerce');
        parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::RUEDUCOMMERCE_FR;
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('RUEDUCOMMERCE_API_URL'),
            'API_KEY' => env('RUEDUCOMMERCE_API_KEY'),
            'API_SHOP_ID' => env('RUEDUCOMMERCE_API_SHOP_ID'),
        ];
    }

}
