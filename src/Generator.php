<?php

namespace Core;

class Generator
{
    public static function generateEmail(): string
    {
        $letters = range('A', 'Z');
        $count = mt_rand(2, 20);
        $output = "";

        for ($i = 0; $i < $count; $i++) {
            $output .= $letters[mt_rand(0, count($letters))];
        }

        return $output . "@mail.ru";
    }

    public static function generateName(): string
    {
        $letters = range('A', 'Z');
        $count = mt_rand(2, 20);
        $output = "";

        for ($i = 0; $i < $count; $i++) {
            $output .= $letters[mt_rand(0, count($letters))];
        }

        return $output;
    }

    public static function generatePassword(): string
    {
        $letters = range('A', 'Z');
        $count = mt_rand(2, 20);
        $output = "";

        for ($i = 0; $i < $count; $i++) {
            $output .= $letters[mt_rand(0, count($letters))];
        }

        return sha1($output);
    }
}
