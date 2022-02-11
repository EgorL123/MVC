<?php

namespace Core\Validator\Password;

use Core\IValidator;
use Core\Validator\AbstractValidator;
use mysql_xdevapi\RowResult;

class PasswordValidator extends AbstractValidator implements IValidator
{
    /**
     * @var int
     */
    public const MIN_PASSWORD_LENGTH = 4;
/**
     * @var int
     */
    public const MAX_PASSWORD_LENGTH = 30;
/**
     * Валидация пароля. Возврат массива ошибок в случае некорректного значения
     * @return int[]
     */

    public function validate(string $password): array
    {
        $this->validatePasswordMaxLen($password);
        $this->validatePasswordMinLen($password);
        return $this->errors;
    }

    public function validatePasswordMinLen(string $password): void
    {
        if (strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $errors[] = PASSWORD_INCORRECT_LENGTH_MIN;
        }
    }

    public function validatePasswordMaxLen(string $password): void
    {
        if (strlen($password) > self::MAX_PASSWORD_LENGTH) {
            $errors[] = PASSWORD_INCORRECT_LENGTH_MAX;
        }
    }
}
