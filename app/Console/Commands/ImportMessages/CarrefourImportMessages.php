<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

class CarrefourImportMessages extends AbstractMiraklImportMessage
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'carrefour');
        return parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::CARREFOUR_FR;
    }

    protected function getSnakeChannelName(): string
    {
        return (new \App\Models\Channel\Channel)->getSnakeName(ChannelEnum::CARREFOUR_FR);
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('CARREFOUR_API_URL'),
            'API_KEY' => env('CARREFOUR_API_KEY'),
            'API_SHOP_ID' => env('CARREFOUR_API_SHOP_ID'),
        ];
    }

}
