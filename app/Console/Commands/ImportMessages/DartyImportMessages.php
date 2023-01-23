<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

class DartyImportMessages extends AbstractMiraklImportMessage
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'darty');
        return parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return (new \App\Models\Channel\Channel)->getSnakeName(ChannelEnum::DARTY_COM);
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('DARTY_API_URL'),
            'API_KEY' => env('DARTY_API_KEY'),
            'API_SHOP_ID' => env('DARTY_API_SHOP_ID'),
        ];
    }
}
