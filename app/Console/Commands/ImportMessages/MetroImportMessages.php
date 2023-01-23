<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

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
        return (new \App\Models\Channel\Channel)->getSnakeName(ChannelEnum::METRO_FR);
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
