<?php

namespace App\Console\Commands\ImportMessages\Mirakl;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;

class MetroImportMessages extends AbstractMiraklImportMessage
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'metro');
        return parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::METRO_FR;
    }

    protected function getSnakeChannelName(): string
    {
        return (new Channel)->getSnakeName(ChannelEnum::METRO_FR);
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('METRO_API_URL'),
            'API_KEY' => env('METRO_API_KEY'),
            'API_SHOP_ID' => env('METRO_API_SHOP_ID'),
        ];
    }

}
