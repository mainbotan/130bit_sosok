<?php

namespace App\Helpers;

class RemoveAvailableMarkets
{
    public static function clean(array $input): array
    {
        foreach ($input as $key => &$value) {
            if ($key === 'available_markets') {
                unset($input[$key]);
                continue;
            }

            if (is_array($value)) {
                $value = self::clean($value);
            }
        }

        return $input;
    }
}
