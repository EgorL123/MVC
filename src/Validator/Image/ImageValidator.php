<?php

namespace Core\Validator\Image;

use Core\IValidator;
use Core\Validator\AbstractValidator;

class ImageValidator extends AbstractValidator implements IValidator
{
    /**
     * @var string[]|null[]
     */
    protected const ALLOWED_IMAGE_TYPES =
        [
            'jpg',
            'png',
            'jpeg',
            null
        ];
/**
     * Проверка входящего изображения на корректный тип
     * @return mixed[]
     */
    public function validate(string $type): ?array
    {
        $this->validateExtension($type);
        return $this->errors;
    }

    public function validateExtension(string $ext): void
    {
        if (!in_array($ext, self::ALLOWED_IMAGE_TYPES)) {
            $this->errors[] = INCORRECT_IMAGE_TYPE;
        }
    }
}
