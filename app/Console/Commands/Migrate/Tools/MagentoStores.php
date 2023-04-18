<?php

namespace App\Console\Commands\Migrate\Tools;

use App\Enums\Channel\ChannelEnum;
use App\Models\Channel\Channel;

abstract class MagentoStores
{
    private static array $store_mapping;
    private static array $api_marketplace_mapping;

    public static function getStoreMapping(): array
    {
        if (!isset(self::$store_mapping)) {
            self::$store_mapping = [
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
                "carrefour"     => Channel::getByName(ChannelEnum::CARREFOUR_FR, false),
//                "leclerc"       => Channel::getByName(ChannelEnum::E_LECLERC, false),
//                "icoza"         => Channel::getByName(ChannelEnum::ICOZA_FR, false),
//                "boulanger"     => Channel::getByName(ChannelEnum::BOULANGER_COM, false),
            ];
        }

        return self::$store_mapping;
    }

    public static function getApiMarketplaceMapping(): array
    {
        if (!isset(self::$api_marketplace_mapping)) {
            self::$api_marketplace_mapping = [
                "cdiscount"     => Channel::getByName(ChannelEnum::CDISCOUNT_FR, false),
                "fnac"          => Channel::getByName(ChannelEnum::FNAC_COM, false),
                "priceminister" => Channel::getByName(ChannelEnum::RAKUTEN_COM, false),
                "darty"         => Channel::getByName(ChannelEnum::DARTY_COM, false),
                "ubaldi"        => Channel::getByName(ChannelEnum::UBALDI_COM, false),
                "conforama"     => Channel::getByName(ChannelEnum::CONFORAMA_FR, false),
                "but"           => Channel::getByName(ChannelEnum::BUT_FR, false),
                "rueducommerce" => Channel::getByName(ChannelEnum::RUEDUCOMMERCE_FR, false),
                "metro"         => Channel::getByName(ChannelEnum::METRO_FR, false),
                "leclerc"       => Channel::getByName(ChannelEnum::E_LECLERC, false),
                "icoza"         => Channel::getByName(ChannelEnum::ICOZA_FR, false),
                "carrefour"     => Channel::getByName(ChannelEnum::CARREFOUR_FR, false),
                "boulanger"     => Channel::getByName(ChannelEnum::BOULANGER_COM, false),
            ];
        }

        return self::$api_marketplace_mapping;
    }
}
