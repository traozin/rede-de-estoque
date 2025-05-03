<?php

namespace App\Helpers;
 
class ArrayUtils {
    
    public static function clearArray(array $array, bool $removeFalse = false): array {
        foreach ($array as $key => &$value) {
            if (is_array($value)) {
                $array[$key] = self::clearArray($value, $removeFalse);

                if (empty($array[$key])) {
                    unset($array[$key]);
                }
            } else {
                $processedValue = $value;

                if ($processedValue === null || $processedValue === '' || ($removeFalse && $processedValue === false)) {
                    unset($array[$key]);
                } else {
                    $array[$key] = $processedValue;
                }
            }
        }
        return $array;
    }

}