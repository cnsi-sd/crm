<?php

namespace App\Jobs\SendMessage;

class UbaldiSendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('UBALDI_API_URL'),
            'API_KEY' => env('UBALDI_API_KEY'),
            'API_SHOP_ID' => env('UBALDI_API_SHOP_ID'),
        ];
    }
}
