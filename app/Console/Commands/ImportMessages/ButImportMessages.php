<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

class ButImportMessages extends AbstractMiraklImportMessages
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'but');
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getChannelName(): string
    {
        return ChannelEnum::BUT_FR;
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('BUT_API_URL'),
            'API_KEY' => env('BUT_API_KEY'),
            'API_SHOP_ID' => env('BUT_API_SHOP_ID'),
        ];
    }
}
