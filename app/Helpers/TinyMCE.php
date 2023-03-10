<?php

namespace App\Helpers;

abstract class TinyMCE
{
    public static function toHtml(string $string): string
    {
        return nl2br($string);
    }

    public static function toText(string $string): string
    {
        $string = str_replace([
            '<br>',
            '<br/>',
            '<br />',
        ], "\n", $string);

        $string = html_entity_decode($string);

        return strip_tags($string);
    }
}
