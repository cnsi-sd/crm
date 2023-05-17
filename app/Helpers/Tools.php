<?php

namespace App\Helpers;

class Tools
{
    /**
     * Normalize string  with lower case and only a -> z chars
     * @param String $subject
     */
    public static function normalize($subject)
    {
        $newSubject = '';
        $subject = strtolower($subject);

        for ($i = 0; $i < strlen($subject); $i++) {
            if ((ord($subject[$i]) >= 97) and (ord($subject[$i]) <= 122)) {
                $newSubject .= $subject[$i];
            }
        }

        return $newSubject;
    }
}
