<?php

namespace App\Jobs\SendMessage;

class DartySendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'host'       => env('DARTY_API_URL'),
            'shop_id'    => env('DARTY_API_SHOP_ID'),
            'key'        => env('DARTY_API_KEY'),
            'partner_id' => env('DARTY_API_PARTNER_ID'),
        ];
    }
}
