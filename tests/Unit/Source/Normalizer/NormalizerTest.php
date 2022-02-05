<?php

namespace Normalizer;

class NormalizerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Проверка ввода текста с пробелами email
     * @dataProvider additionalProviderNormalizeSpaces
     */
    public function testNormalizerSpaces(string $text, string $expected): void
    {
        $this->assertEquals($expected, \Core\Normalizer::normalizeSpaces($text));
    }

    /**
     * @codeCoverageIgnore
     * @return array<int, array<int|string, string>>
     */
    public function additionalProviderNormalizeSpaces(): array
    {
        return
            [
                ['spaces' => '   t e  s  t @m  ai l.ru  ', 'test@mail.ru'],
            ];
    }

    /**
     * Проверка удаления спецсимволов при попытке XSS инъекции
     * @dataProvider additionalProviderNormalizeSpecialChars
     * @param string[] $text
     * @param string[] $expected
     */
    public function testNormalizerSpecialChars(array $text, array $expected): void
    {
        \Core\Normalizer::normalizeSpecialChars($text);

        $this->assertEquals($expected, $text);
    }

    /**
     * @codeCoverageIgnore
     * @return array<int, array<int|string, string>>
     */
    public function additionalProviderNormalizeSpecialChars(): array
    {
        return
            [
                ['data' => ['<script></script><br>'], ['&lt;script&gt;&lt;/script&gt;&lt;br&gt;']],
            ];
    }
}
