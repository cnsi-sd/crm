<?php

namespace App\Helpers;

abstract class JS
{
    /**
     * @param $key
     * @param $value
     * @return string
     */
    public static function define($key, $value = null)
    {
        $variables = is_array($key) ? $key : [$key => $value];
        $html = '<script type="text/javascript">';

        foreach ($variables as $key => $value)
            $html .= 'let ' . $key . ' = ' . json_encode($value) . ';';

        $html .= '</script>';
        return $html;
    }
}
