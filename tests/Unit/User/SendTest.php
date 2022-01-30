<?php

class SendTest extends \PHPUnit\Framework\TestCase
{
    private $object;

    public function setUp(): void
    {
        $this->object = new \App\Model\User(
            '',
            '',
            \Core\Generator::generateName(),
            \Core\Generator::generateEmail(),
            ''
        );
    }

    /**
     * Проверка отправки пользователя
     */
    public function testSend(): void
    {
        $this->expectException(Exception::class);

        $this->assertEquals(1, $this->object->save());
        $this->object->save();
    }
}
