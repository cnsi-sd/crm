<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;

class ShowroomImportMessages extends AbstractMiraklImportMessages
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'showroom');
        parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::SHOWROOMPRIVE_COM;
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
