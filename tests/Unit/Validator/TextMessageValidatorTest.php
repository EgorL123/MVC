<?php

class TextMessageValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Проверка работы функции при некорректном значении текста сообщения
     * @dataProvider additionalProviderValidateIncorrectTextLength
     */
    public function testValidateInCorrectTextLength(string $text): void
    {
        $errors = [MESSAGE_TEXT_INCORRECT_LENGTH_MAX];

        foreach (\Core\Validator::validateMessageText($text) as $errorCode) {
            $this->assertTrue(in_array($errorCode, $errors));
        }
    }

    /**
     * @return array<int, array<string, bool|string>>
     */
    public function additionalProviderValidateIncorrectTextLength(): array
    {
        return [
            ['text' => file_get_contents('longText.txt', 1)],

        ];
    }
}
