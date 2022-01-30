<?php

class SpacesNormalizerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Проверка ввода текста с пробелами email
     * @dataProvider additionalProviderValidateIncorrectEmail
     */
    public function testNormalizerSpaces(string $text, string $expected): void
    {
        $this->assertEquals($expected, \Core\Normalizer::normalizeSpaces($text));
    }

    /**
     * @return array<int, array<int|string, string>>
     */
    public function additionalProviderValidateIncorrectEmail(): array
    {
        return
            [
                ['spaces' => '   t e  s  t @m  ai l.ru  ', 'test@mail.ru'],

            ];
    }
}
