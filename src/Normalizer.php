<?php

namespace Core;

class Normalizer
{
    /**
     * Удаление  пробелов
     */
    public static function normalizeSpaces(IField $field): string
    {
        $pattern = "/\s/";

        return preg_replace($pattern, '', $field->get());
    }

    /**
     * Замена спецсимволов
     */
    public static function normalizeSpecialChars(IField $field): string
    {
        return htmlspecialchars($field->get());
    }
}
