<?php

class EmptyFieldsValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Проверка работы функции для получения непустых полей
     * @dataProvider additionalProviderValidateNotEmptyFieldsIncorrect
     * @param array<string, string> $data
     * @param string[] $expected
     * @param mixed[]|string[] $excluded
     */
    public function testValidateNotEmptyIncorrect(array $data, array $expected, array $excluded): void
    {


        foreach (\Core\Validator::validateFormNotEmpty($data, $excluded) as $notEmptyField) {
            $this->assertTrue(in_array($notEmptyField, $expected));
        }
    }

    /**
     * @return array<int, mixed[]>
     */
    public function additionalProviderValidateNotEmptyFieldsIncorrect(): array
    {
        return
            [
                [
                    'fields' =>
                        ['name' => '', 'email' => '', 'password' => '12', 'password_repeat' => '12'],
                    ['password', 'password_repeat'],
                    []
                ],
                [
                    'fields_with_excluded' =>
                        ['name' => '', 'email' => '', 'password' => '12', 'password_repeat' => '12'],
                    ['password'],
                    ['password_repeat']
                ],
            ];
    }


    /**
     * Проверка работы функции для получения ошибок у пустых полей
     * @dataProvider additionalProviderValidateEmptyFieldsIncorrect
     * @param array<string, string> $data
     */
    public function testValidateEmptyIncorrect(array $data): void
    {

        $errors = [EMPTY_EMAIL_FORM, EMPTY_PASSWORD_FORM, EMPTY_NAME_FORM];

        foreach (\Core\Validator::validateFormEmpty($data) as $emptyFieldErr) {
            $this->assertTrue(in_array($emptyFieldErr, $errors));
        }
    }

    /**
     * @return array<int, array<string, array<string, string>>>
     */
    public function additionalProviderValidateEmptyFieldsIncorrect(): array
    {
        return
            [
               ['fields' => ['name' => '', 'email' => '', 'password' => '', 'password_repeat' => ''],
               ],

        ];
    }
}
