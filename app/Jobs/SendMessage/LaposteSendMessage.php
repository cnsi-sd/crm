<?php

namespace App\Jobs\SendMessage;

class LaposteSendMessage extends AbstractMiraklSendMessage
{
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('LAPOSTE_API_URL'),
            'API_KEY' => env('LAPOSTE_API_KEY'),
            'API_SHOP_ID' => env('LAPOSTE_API_SHOP_ID'),
        ];
    }
}
