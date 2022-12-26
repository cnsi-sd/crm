<?php

namespace App\Jobs\SendMessage;

class ButSendMessage extends AbstractSendMessage
{
    public function __construct()
    {
        return parent::__construct($this->message);
    }
    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('BUT_API_URL'),
            'API_KEY' => env('BUT_API_KEY'),
            'API_SHOP_ID' => env('BUT_API_SHOP_ID'),
        ];
    }
}
