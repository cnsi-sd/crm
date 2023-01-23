<?php

namespace App\Jobs\SendMessage;

class ButSendMessage extends AbstractMiraklSendMessage
{
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('BUT_API_URL'),
            'API_KEY' => env('BUT_API_KEY'),
            'API_SHOP_ID' => env('BUT_API_SHOP_ID'),
        ];
    }
}
