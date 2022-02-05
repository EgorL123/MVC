<?php

namespace Validator;
use const INCORRECT_IMAGE_TYPE;

class ImageValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Проверка работы функции валидации расширения получаемого файла
     * при некорректном значении
     * @dataProvider additionalProviderValidateIncorrectImage
     */
    public function testValidateIncorrectImage(string $extension): void
    {
        $errors = [INCORRECT_IMAGE_TYPE];

        foreach (\Core\Validator::validateImageType($extension) as $errorCode) {
            $this->assertTrue(in_array($errorCode, $errors));
        }
    }

    /**
     * @codeCoverageIgnore
     * @return array<int, array<string, string>>
     */
    public function additionalProviderValidateIncorrectImage(): array
    {
        return
            [
                ['not_allowed_extension' => 'exe'],
            ];
    }

    /**
     * Проверка работы функции валидации расширения получаемого файла
     * при корректном значении
     * @dataProvider additionalProviderValidateCorrectImage
     */
    public function testValidateCorrectImage(string $extension): void
    {
        $this->assertTrue(empty(\Core\Validator::validateImageType(\Core\Normalizer::normalizeSpaces($extension))));
    }

    /**
     * @codeCoverageIgnore
     * @return array<int, mixed[]>
     */
    public function additionalProviderValidateCorrectImage(): array
    {
        return
            [
                ['jpeg_extension' => 'jpeg'],
                ['png_extension' => 'png']

            ];
    }
}
