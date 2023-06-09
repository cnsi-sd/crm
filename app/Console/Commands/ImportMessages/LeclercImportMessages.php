<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;

class LeclercImportMessages extends AbstractMiraklImportMessages
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'leclerc');
        parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::E_LECLERC;
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('LECLERC_API_URL'),
            'API_KEY' => env('LECLERC_API_KEY'),
            'API_SHOP_ID' => env('LECLERC_API_SHOP_ID'),
        ];
    }

}
