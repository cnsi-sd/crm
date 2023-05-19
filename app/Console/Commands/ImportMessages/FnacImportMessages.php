<?php

namespace App\Console\Commands\ImportMessages;

use App\Enums\Channel\ChannelEnum;
use Exception;

class FnacImportMessages extends AbstractBompImportMessages
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->signature = sprintf($this->signature, 'fnac');
        parent::__construct();
    }

    protected function getChannelName(): string
    {
        return ChannelEnum::FNAC_COM;
    }

    protected function getCredentials(): array
    {
        return [
            'host'       => env('FNAC_API_URL'),
            'shop_id'    => env('FNAC_API_SHOP_ID'),
            'key'        => env('FNAC_API_KEY'),
            'partner_id' => env('FNAC_API_PARTNER_ID'),
        ];
    }
}
