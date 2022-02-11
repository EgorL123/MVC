<?php

namespace Core\Validator\Name;

use Core\IValidator;
use Core\Validator\AbstractValidator;

class NameValidator extends AbstractValidator implements IValidator
{
    /**
     * @var int
     */
    protected const MIN_NAME_LENGTH = 4;


    /**
     * @var int
     */
    protected const MAX_NAME_LENGTH = 50;

    /**
     * Валидация имени
     * @return int[]
     */
    public function validate(string $name): ?array
    {
        $this->validateNameMaxLen($name);
        $this->validateNameMinLen($name);

        return $this->errors;
    }

    /**
     * @return bool
     */
    public function validateNameMinLen(string $name): void
    {
        if (strlen($name) < self::MIN_NAME_LENGTH) {
            $this->errors[] = NAME_INCORRECT_LENGTH_MIN;
        }
    }

    /**
     * @return bool
     */
    public function validateNameMaxLen(string $name): void
    {
        if (strlen($name) > self::MAX_NAME_LENGTH) {
            $this->errors[] = NAME_INCORRECT_LENGTH_MAX;
        }
    }
}
