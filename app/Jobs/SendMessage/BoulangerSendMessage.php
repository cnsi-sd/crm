<?php

namespace App\Jobs\SendMessage;

class BoulangerSendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('BOULANGER_API_URL'),
            'API_KEY' => env('BOULANGER_API_KEY'),
            'API_SHOP_ID' => env('BOULANGER_API_SHOP_ID'),
        ];
    }
}
