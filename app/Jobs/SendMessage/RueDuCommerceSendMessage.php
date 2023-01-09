<?php

namespace App\Jobs\SendMessage;

class RueDuCommerceSendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('RUEDUCOMMERCE_API_URL'),
            'API_KEY' => env('RUEDUCOMMERCE_API_KEY'),
            'API_SHOP_ID' => env('RUEDUCOMMERCE_API_SHOP_ID'),
        ];
    }
}
