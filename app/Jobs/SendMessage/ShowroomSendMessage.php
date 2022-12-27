<?php

namespace App\Jobs\SendMessage;

class ShowroomSendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('SHOWROOM_API_URL'),
            'API_KEY' => env('SHOWROOM_API_KEY'),
            'API_SHOP_ID' => env('SHOWROOM_API_SHOP_ID'),
        ];
    }
}
