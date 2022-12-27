<?php

namespace App\Jobs\SendMessage;

class ConforamaSendMesssage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('CONFORAMA_API_URL'),
            'API_KEY' => env('CONFORAMA_API_KEY'),
            'API_SHOP_ID' => env('CONFORAMA_API_SHOP_ID'),
        ];
    }
}
