<?php

namespace Core\Validator\Email;

use Core\IValidator;
use Core\Validator\AbstractValidator;

class EmailValidator extends AbstractValidator implements IValidator
{
    /**
     * @var int
     */
    protected const MIN_EMAIL_LENGTH = 1;

    /**
     * @var int
     */
    protected const MAX_EMAIL_LENGTH = 255;

    /**
     * Валидация email
     * @param string $email
     */
    public function validate(string $subject): ?array
    {
        $this->validateEmailLengthMin($subject);
        $this->validateEmailLengthMax($subject);
        $this->validateEmailSpecialChars($subject);

        return $this->errors;
    }

    /**
     * @return bool
     */
    public function validateEmailLengthMin(string $email): void
    {
        if (strlen($email) < self::MIN_EMAIL_LENGTH) {
            $this->errors[] = EMAIL_INCORRECT_LENGTH_MIN;
        }
    }

    /**
     * @return bool
     */
    public function validateEmailLengthMax(string $email): void
    {
        if (strlen($email) > self::MAX_EMAIL_LENGTH) {
            $this->errors[] = EMAIL_INCORRECT_LENGTH_MAX;
        }
    }

    /**
     * @return bool
     */
    public function validateEmailSpecialChars(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = INCORRECT_EMAIL;
        }
    }
}
