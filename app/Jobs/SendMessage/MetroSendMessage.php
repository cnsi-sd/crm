<?php

namespace App\Jobs\SendMessage;

class MetroSendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('METRO_API_URL'),
            'API_KEY' => env('METRO_API_KEY'),
            'API_SHOP_ID' => env('METRO_API_SHOP_ID'),
        ];
    }
}
