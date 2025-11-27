<?php

namespace App\Core;

use Illuminate\Support\Str;

class Util
{
    public static function convertKeysToSnakeCase(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $snakeKey = Str::snake($key);
            if (is_array($value)) {
                $value = self::convertKeysToSnakeCase($value);
            }
            $result[$snakeKey] = $value;
        }
        return $result;
    }

    public static function convertKeysToCamelCase(array $array)
    {
        $result = [];
        foreach ($array as $key => $value) {
            $camelKey = Str::camel($key);
            if (is_array($value)) {
                $value = self::convertKeysToCamelCase($value);
            }
            $result[$camelKey] = $value;
        }
        return $result;
    }
}
