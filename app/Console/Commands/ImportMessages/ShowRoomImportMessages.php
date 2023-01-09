<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

class ShowRoomImportMessages extends AbstractMiraklImportMessage
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'showroom');
        return parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return (new \App\Models\Channel\Channel)->getSnakeName(ChannelEnum::SHOWROOMPRIVE_COM);
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('SHOWROOM_API_URL'),
            'API_KEY' => env('SHOWROOM_API_KEY'),
            'API_SHOP_ID' => env('SHOWROOM_API_SHOP_ID'),
        ];
    }

}
