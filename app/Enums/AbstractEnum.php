<?php

namespace App\Enums;

use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionException;

abstract class AbstractEnum
{
    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getList(): array
    {
        $child_class = self::getChildClass();

        if (!is_null($child_class))
            return $child_class->getConstants();

        return [];
    }

    /**
     * @return ReflectionClass
     * @throws ReflectionException
     */
    private static function getChildClass(): ReflectionClass
    {
        return new ReflectionClass(get_called_class());
    }

    /**
     * @param string $value
     * @return bool
     * @throws ReflectionException
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::getList());
    }

    /**
     * @param $value
     * @throws Exception
     */
    public static function validOrThrow($value): void
    {
        if (!self::isValid($value)) {
            $enum_name = self::getChildClass()->getShortName();
            $message = $value . ' is not a valid ' . $enum_name . '. Please use a valid ' . $enum_name . ' : ' . implode(', ', self::getList());

            throw new Exception($message);
        }
    }

    /**
     * @throws ReflectionException
     */
    public static function getMessage($not_translated_key, $trans_choice_count = null) : string
    {
        $child_class = self::getChildClass();
        if (is_null($child_class))
            return $not_translated_key;

        $list = self::getList();
        if (!isset($list[$not_translated_key])) { // Try to search if the provided param is a value
            $keys = array_filter($list, function ($value) use ($not_translated_key) {
                return $value === $not_translated_key;
            });
            if (count($keys) <= 0)
                return $not_translated_key; // Abort and return the non translated key

            $not_translated_key = current(array_keys($keys));
        }

        $enum_name = self::getChildClass()->getShortName();
        $translation_file = Str::snake($enum_name);
        $full_translation_key = 'enums/' . $translation_file . '.' . $not_translated_key;

        if (Lang::has($full_translation_key)) {
            if (is_null($trans_choice_count))
                return __($full_translation_key);
            else
                return trans_choice($full_translation_key, $trans_choice_count);
        }

        return $not_translated_key;
    }

    /**
     * @param bool $sort
     * @param int|null $trans_choice_count
     * @return array
     * @throws ReflectionException
     */
    public static function getTranslatedList(bool $sort = true, ?int $trans_choice_count = null): array
    {
        $list = self::getList();
        $translated_list = [];

        foreach ($list as $key => $value)
            $translated_list[$key] = self::getMessage($key, $trans_choice_count);

        if ($sort)
            asort($translated_list);

        return $translated_list;
    }
}
