<?php

namespace App\Jobs\SendMessage;

class LeclercSendMessage extends AbstractMiraklSendMessage
{

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('LECLERC_API_URL'),
            'API_KEY' => env('LECLERC_API_KEY'),
            'API_SHOP_ID' => env('LECLERC_API_SHOP_ID'),
        ];
    }
}
