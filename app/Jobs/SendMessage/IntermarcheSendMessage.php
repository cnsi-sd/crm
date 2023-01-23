<?php

namespace App\Jobs\SendMessage;

class IntermarcheSendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('INTERMARCHE_API_URL'),
            'API_KEY' => env('INTERMARCHE_API_KEY'),
            'API_SHOP_ID' => env('INTERMARCHE_API_SHOP_ID'),
        ];
    }
}
