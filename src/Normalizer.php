<?php

namespace Core;

class Normalizer
{
    public static function normalizeSpaces($str): string
    {
        $pattern = "/\s/";

        return preg_replace($pattern, '', $str);
    }

    public static function normalizaSpecialChars(array &$data): void
    {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars($value);
        }
    }
}
