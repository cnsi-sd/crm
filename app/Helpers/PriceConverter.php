<?php

namespace App\Helpers;
use Illuminate\Support\Str;

abstract class PriceConverter {
    public static function stringToFloat(string $value, bool $absolute = false) : float {
        $is_negative = Str::contains($value, '-');

        $value = str_replace('-', '', $value);
        $value = str_replace(' ', '', $value);
        $value = str_replace('€', '', $value);
        $value = str_replace(',', '.', $value);

        // Delete all chars wich are not digit or decimal point
        $value = preg_replace('/[^\d.]/', '', $value);

        // Parse to float
        $value = floatval($value);

        // Restore the value sign
        if($is_negative && !$absolute)
            $value = -$value;

        return $value;
    }

    public static function floatToString($value, string $currency = '€', $round = null): string
    {
        if (is_null($value))
            return '--';

        if($round)
            $value = round($value, $round);

        return str_replace('.', ',', $value) . ' ' . $currency;

    }

    public static function withThousandSeparator($value, string $currency = '€', $round = null): string
    {
        if (is_null($value))
            return '--';

        if(!is_float($value))
            $value = floatval(preg_replace('/[^\d.]/', '', $value));

        if($round)
            $value = round($value, $round);

        return number_format($value, 2, ',', ' '). ' ' .$currency;
    }
}
