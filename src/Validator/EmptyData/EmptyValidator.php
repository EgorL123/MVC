<?php

namespace Core\Validator\EmptyData;

use Core\IValidator;
use Core\Validator\AbstractValidator;

class EmptyValidator extends AbstractValidator implements IValidator
{
    /**
     * @var array<string, int>
     */
    protected const FIELD_EMPTY_ERROR_CORRESPOND =
        [
            'name' => EMPTY_NAME_FORM,
            'email' => EMPTY_EMAIL_FORM,
            'password' => EMPTY_PASSWORD_FORM,
            'password_repeat' => EMPTY_PASSWORD_FORM
        ];
    public function validate(string $subject): bool
    {
        if (empty($subject)) {
            $this->errors[] = EMPTY_FIELDS;
        }

        return true;
    }

    /**
     * Проверка полей формы на пустоту. Возвращает массив непустых полей, которых нет в
     * массиве исключенных полей
     * @return int[]|string[]
     */
    public function validateFormNotEmpty(array $data, array $excludedFileds): array
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
    public function validateAll(array $data): bool
    {

        foreach ($data as $key => $field) {
            if (empty($field)) {
                $this->errors[] = self::FIELD_EMPTY_ERROR_CORRESPOND[$key];
            }
        }

        return true;
    }
}
