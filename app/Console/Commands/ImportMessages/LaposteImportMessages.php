<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;

class LaposteImportMessages extends AbstractMiraklImportMessages
{
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'laposte');
        parent::__construct();
    }

    /**
     * @return string
     */
    protected function getChannelName(): string
    {
        return ChannelEnum::LAPOSTE_FR;
    }

    /**
     * @return array
     */
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('LAPOSTE_API_URL'),
            'API_KEY' => env('LAPOSTE_API_KEY'),
            'API_SHOP_ID' => env('LAPOSTE_API_SHOP_ID'),
        ];
    }


}
