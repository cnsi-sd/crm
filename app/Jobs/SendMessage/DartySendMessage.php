<?php

namespace App\Jobs\SendMessage;

class DartySendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('DARTY_API_URL'),
            'API_KEY' => env('DARTY_API_KEY'),
            'API_SHOP_ID' => env('DARTY_API_SHOP_ID'),
        ];
    }
}
