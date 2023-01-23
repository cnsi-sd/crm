<?php

namespace App\Jobs\SendMessage;

class CarrefourSendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('CARREFOUR_API_URL'),
            'API_KEY' => env('CARREFOUR_API_KEY'),
            'API_SHOP_ID' => env('CARREFOUR_API_SHOP_ID'),
        ];
    }
}
