<?php

namespace Core;

class Normalizer
{
    /**
     * Удаление лишних пробелов
     * @param $str
     */
    public static function normalizeSpaces($str): string
    {
        $pattern = "/\s/";

        return preg_replace($pattern, '', $str);
    }

    /**
     * замена спецсимволов
     */
    public static function normalizeSpecialChars(array &$data): void
    {
        foreach ($data as $key => $value) {
            $data[$key] = htmlspecialchars($value);
        }
    }
}
