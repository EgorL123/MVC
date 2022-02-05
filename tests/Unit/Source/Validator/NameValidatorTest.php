<?php

namespace Validator;
use const NAME_INCORRECT_LENGTH_MAX;
use const NAME_INCORRECT_LENGTH_MIN;

class NameValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Проверка работы функции валидации при некорректном имени
     * @dataProvider additionalProviderValidateIncorrectName
     */
    public function testValidateIncorrectName(string $name): void
    {
        $errors = [NAME_INCORRECT_LENGTH_MIN, NAME_INCORRECT_LENGTH_MAX];

        foreach (\Core\Validator::validateName($name) as $errorCode) {
            $this->assertTrue(in_array($errorCode, $errors));
        }
    }

    /**
     * @codeCoverageIgnore
     * @return array<int, mixed[]>
     */
    public function additionalProviderValidateIncorrectName(): array
    {
        return
            [
                ['incorrect_length_min' => 'N'],
                ['incorrect_length_max' => 'NAME1NAME1NAME1NAME11111111111111111111111111111111111111111111111111'],
            ];
    }
}
