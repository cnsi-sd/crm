<?php

namespace App\Console\Commands\Migrate\Tools;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;

abstract class MagentoStores
{
    public static function getMapping(): array
    {
        return [
            "amazon"        => Channel::getByName(ChannelEnum::AMAZON_FR, false)->id,
            "cdiscount"     => Channel::getByName(ChannelEnum::CDISCOUNT_FR, false)->id,
            "fnac"          => Channel::getByName(ChannelEnum::FNAC_COM, false)->id,
            "priceminister" => Channel::getByName(ChannelEnum::RAKUTEN_COM, false)->id,
            "darty"         => Channel::getByName(ChannelEnum::DARTY_COM, false)->id,
            "ubaldi"        => Channel::getByName(ChannelEnum::UBALDI_COM, false)->id,
            "conforama"     => Channel::getByName(ChannelEnum::CONFORAMA_FR, false)->id,
            "but"           => Channel::getByName(ChannelEnum::BUT_FR, false)->id,
            "rueducommerce" => Channel::getByName(ChannelEnum::RUEDUCOMMERCE_FR, false)->id,
            "metro"         => Channel::getByName(ChannelEnum::METRO_FR, false)->id,
            "leclerc"       => Channel::getByName(ChannelEnum::E_LECLERC, false)->id,
            "icoza"         => Channel::getByName(ChannelEnum::ICOZA_FR, false)->id,
        ];
    }
}
