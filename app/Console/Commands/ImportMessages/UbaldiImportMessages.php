<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

class   UbaldiImportMessages extends AbstractMiraklImportMessage
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'ubaldi');
        return parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return (new \App\Models\Channel\Channel)->getSnakeName(ChannelEnum::UBALDI_COM);
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('UBALDI_API_URL'),
            'API_KEY' => env('UBALDI_API_KEY'),
            'API_SHOP_ID' => env('UBALDI_API_SHOP_ID'),
        ];
    }

}
