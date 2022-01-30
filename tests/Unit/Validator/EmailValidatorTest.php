<?php

class EmailValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Проверка ввода некорректного email
     * @dataProvider additionalProviderValidateIncorrectEmail
     */
    public function testValidateIncorrectEmail(string $email): void
    {
        $errors = [EMAIL_INCORRECT_LENGTH_MAX, EMAIL_INCORRECT_LENGTH_MIN, INCORRECT_EMAIL];

        foreach (\Core\Validator::validateEmail($email) as $errorCode) {
            $this->assertTrue(in_array($errorCode, $errors));
        }
    }

    /**
     * @return array<int, mixed[]>
     */
    public function additionalProviderValidateIncorrectEmail(): array
    {
        return
            [
               ['missing_required_symbols_1' => 'testmail.ru'],
               ['missing_required_symbols_2' => 'test@.ru'],
               ['missing_required_symbols_3' => 'test@mail'],
               ['missing_required_symbols_4' => '@mail'],
               ['incorrect_length_min' => '@'],
               ['incorrect_length_max' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa@mail.ru'],
        ];
    }
}
