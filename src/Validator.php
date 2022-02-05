<?php

namespace Core;

use PHPUnit\Util\RegularExpressionTest;

class Validator
{
    public const MIN_PASSWORD_LENGTH = 4;
    public const MIN_NAME_LENGTH = 4;
    public const MAX_PASSWORD_LENGTH = 30;
    public const MAX_NAME_LENGTH = 50;
    public const MAX_TEXT_LENGTH = 255;
    public const MIN_EMAIL_LENGTH = 1;
    public const ALLOWED_IMAGE_TYPES =
        [
            'jpg',
            'png',
            'jpeg',
            null

        ];

    public const FIELD_EMPTY_ERROR_CORRESPOND =
        [
            'name' => EMPTY_NAME_FORM,
            'email' => EMPTY_EMAIL_FORM,
            'password' => EMPTY_PASSWORD_FORM,
            'password_repeat' => EMPTY_PASSWORD_FORM
        ];


    /**
     * Проверка полей формы на пустоту. Возвращает массив непустых полей, которых нет в
     * массиве исключенных полей
     * @return int[]|string[]
     */
    public static function validateFormNotEmpty(array $data, array $excludedFileds): array
    {
        $emptyFields = [];
        foreach ($data as $key => $field) {
            if (!empty($field) && !in_array($key, $excludedFileds)) {
                $emptyFields[] = $key;
            }
        }

        return $emptyFields;
    }

    /**
     * Проверка полей формы на пустоту. Возвращает массив ошибок пустых полей
     * @return int[]
     */
    public static function validateFormEmpty(array $data): array
    {
        $errors = [];

        foreach ($data as $key => $field) {
            if (empty($field)) {
                $errors[] = self::FIELD_EMPTY_ERROR_CORRESPOND[$key];
            }
        }

        return $errors;
    }

    /**
     * Валидация email
     * @return bool
     */
    public static function validateEmail(string $email): array
    {
        $errors = [];
        $emailLength = strlen($email);

        if ($emailLength < self::MIN_EMAIL_LENGTH) {
            $errors[] = EMAIL_INCORRECT_LENGTH_MIN;
        }

        if ($emailLength > self::MAX_TEXT_LENGTH) {
            $errors[] = EMAIL_INCORRECT_LENGTH_MAX;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = INCORRECT_EMAIL;
        }

        return $errors;
    }

    /**
     * Валидация пароля. Возврат массива ошибок в случае некорректного значения
     * @return int[]
     */
    public static function validatePassword(string $password): array
    {
        $errors = [];
        $passwordLength = strlen($password);

        if ($passwordLength < self::MIN_PASSWORD_LENGTH) {
            $errors[] = PASSWORD_INCORRECT_LENGTH_MIN;
        }

        if ($passwordLength > self::MAX_PASSWORD_LENGTH) {
            $errors[] = PASSWORD_INCORRECT_LENGTH_MAX;
        }

        return $errors;
    }

    /**
     * Валидация имени
     * @return int[]
     */
    public static function validateName(string $name): array
    {
        $nameLength = strlen($name);
        $errors = [];

        if ($nameLength < self::MIN_NAME_LENGTH) {
            $errors[] = NAME_INCORRECT_LENGTH_MIN;
        }

        if ($nameLength > self::MAX_NAME_LENGTH) {
            $errors[] = NAME_INCORRECT_LENGTH_MAX;
        }

        return $errors;
    }


    /**
     * Рекурсивная функция для помещения всех ошибок в единый массив
     * Нужна для обработки неопределенного числа вложенных массивов ошибок
     * для их последующего вывода
     * @return mixed[]
     */
    public static function getErrors(array $errors): array
    {
        $output = [];

        foreach ($errors as $error) {
            if (is_array($error)) {
                $output = self::getErrors($error);
            } else {
                $output[] = $error;
            }
        }

        return $output;
    }

    /**
     * Проверка входящего изображения на корректный тип
     * @return mixed[]
     */
    public static function validateImageType(string $type): array
    {

        $errors = [];
        if (!in_array($type, self::ALLOWED_IMAGE_TYPES)) {
            $errors[] = INCORRECT_IMAGE_TYPE;
        }

        return $errors;
    }

    /**
     * @return mixed[]
     */
    public static function validateMessageText(string $text): array
    {
        $errors = [];

        if (strlen($text) > self::MAX_TEXT_LENGTH) {
            $errors[] = MESSAGE_TEXT_INCORRECT_LENGTH_MAX;
        }

        return $errors;
    }
}
