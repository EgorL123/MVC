<?php

namespace Core\Validator\Message;

use Core\IValidator;
use Core\Validator\AbstractValidator;

class MessageTextValidator extends AbstractValidator implements IValidator
{
    /**
     * @var int
     */
    public const MAX_TEXT_LENGTH = 255;

    /**
     * @var int
     */
    public const MIN_TEXT_LENGTH = 1;

    /**
     * @return mixed[]
     */
    public function validate(string $text): ?array
    {
        $this->validateMaxTextLength($text);
        $this->validateMinTextLength($text);

        return $this->errors;
    }


    public function validateMaxTextLength(string $text): void
    {
        if (strlen($text) > self::MAX_TEXT_LENGTH) {
            $this->errors[] = MESSAGE_TEXT_INCORRECT_LENGTH_MAX;
        }
    }

    public function validateMinTextLength(string $text): void
    {
        if (strlen($text) < self::MIN_TEXT_LENGTH) {
            $this->errors[] = MESSAGE_TEXT_INCORRECT_LENGTH_MIN;
        }
    }
}
