<?php

class PasswordValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Проверка работы функции валидации при некорректном значении пароля
     * @dataProvider additionalProviderValidateIncorrectPassword
     */
    public function testValidateIncorrectPassword(string $password): void
    {
        $errors = [PASSWORD_INCORRECT_LENGTH_MIN, PASSWORD_INCORRECT_LENGTH_MAX];

        foreach (\Core\Validator::validatePassword($password) as $errorCode) {
            $this->assertTrue(in_array($errorCode, $errors));
        }
    }

    /**
     * @return array<int, mixed[]>
     */
    public function additionalProviderValidateIncorrectPassword(): array
    {
        return
            [
               ['incorrect_length_min' => '111'],
               ['incorrect_length_max' => '11111111111111111111111111111111111'],
            ];
    }
}
