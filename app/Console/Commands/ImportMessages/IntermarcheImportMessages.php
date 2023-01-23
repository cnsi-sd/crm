<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

class IntermarcheImportMessages extends AbstractMiraklImportMessage
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'intermarche');
        return parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return (new \App\Models\Channel\Channel)->getSnakeName(ChannelEnum::INTERMARCHE_FR);
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('INTERMARCHE_API_URL'),
            'API_KEY' => env('INTERMARCHE_API_KEY'),
            'API_SHOP_ID' => env('INTERMARCHE_API_SHOP_ID'),
        ];
    }

}
