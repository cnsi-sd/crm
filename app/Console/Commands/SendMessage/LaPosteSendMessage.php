<?php

namespace App\Console\Commands\SendMessage;

use App\Enums\Channel\ChannelEnum;

class LaPosteSendMessage extends ShowroomPriveeSendMessage
{
    //protected $signature = 'laposte:import:messages';
    //protected $description = 'import La poste message';

    //protected $channelId = 2;


    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'laposte');
        return parent::__construct();
    }

    protected function getChannelName(): string
    {
        // TODO: Implement getChannelName() method.
        return ChannelEnum::LAPOSTE_FR;
    }

    protected function getCredentials(): array
    {
        return [
            'API_URL' => env('LAPOSTE_API_URL'),
            'API_KEY' => env('LAPOSTE_API_KEY'),
            'API_SHOP_ID' => env('LAPOSTE_API_SHOP_ID'),
        ];
    }

}
