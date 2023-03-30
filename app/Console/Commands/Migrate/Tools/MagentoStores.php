<?php

namespace App\Console\Commands\Migrate\Tools;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;

abstract class MagentoStores
{
    public static function getMapping(): array
    {
        return [
            "amazon"        => Channel::getByName(ChannelEnum::AMAZON_FR, false),
            "cdiscount"     => Channel::getByName(ChannelEnum::CDISCOUNT_FR, false),
            "fnac"          => Channel::getByName(ChannelEnum::FNAC_COM, false),
            "priceminister" => Channel::getByName(ChannelEnum::RAKUTEN_COM, false),
            "darty"         => Channel::getByName(ChannelEnum::DARTY_COM, false),
            "ubaldi"        => Channel::getByName(ChannelEnum::UBALDI_COM, false),
            "conforama"     => Channel::getByName(ChannelEnum::CONFORAMA_FR, false),
            "but"           => Channel::getByName(ChannelEnum::BUT_FR, false),
            "rueducommerce" => Channel::getByName(ChannelEnum::RUEDUCOMMERCE_FR, false),
            "metro"         => Channel::getByName(ChannelEnum::METRO_FR, false),
//            "leclerc"       => Channel::getByName(ChannelEnum::E_LECLERC, false),
//            "icoza"         => Channel::getByName(ChannelEnum::ICOZA_FR, false),
        ];
    }
}
