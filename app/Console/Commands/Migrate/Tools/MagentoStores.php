<?php

namespace App\Console\Commands\Migrate\Tools;

use App\Enums\Channel\ChannelEnum;

abstract class MagentoStores
{
    public static function getMapping(): array
    {
        return [
            "amazon"        => ChannelEnum::AMAZON_FR,
            "cdiscount"     => ChannelEnum::CDISCOUNT_FR,
            "fnac"          => ChannelEnum::FNAC_COM,
            "priceminister" => ChannelEnum::RAKUTEN_COM,
            "darty"         => ChannelEnum::DARTY_COM,
            "ubaldi"        => ChannelEnum::UBALDI_COM,
            "conforama"     => ChannelEnum::CONFORAMA_FR,
            "but"           => ChannelEnum::BUT_FR,
            "rueducommerce" => ChannelEnum::RUEDUCOMMERCE_FR,
            "metro"         => ChannelEnum::METRO_FR,
            "leclerc"       => ChannelEnum::E_LECLERC,
            "icoza"         => ChannelEnum::ICOZA_FR,
        ];
    }
}
