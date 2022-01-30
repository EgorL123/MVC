<?php

class SendMessageTest extends \PHPUnit\Framework\TestCase
{
    private $object;

    public function setUp(): void
    {
        $this->object = new \App\Model\User(
            '',
            \Core\Generator::generatePassword(),
            \Core\Generator::generateName(),
            \Core\Generator::generateEmail(),
            ''
        );
        $this->object->save();
        $this->object = \App\Model\User::getByEmail($this->object->getEmail());
    }

    /**
     * Тестирование отправки корректного сообщения
     */
    public function testSend(): void
    {
        $this->assertEquals(
            EXPECTED_EXECUTE_QUERY_RESULT,
            \App\Model\Message::send($this->object->getId(), '', '')
        );
    }

    /**
     * Тестирование отправки сообщения с некорректным идентификатором пользователя
     */
    public function testIncorrectSend(): void
    {
        $this->expectException(Exception::class);
        $this->assertEquals(
            EXPECTED_EXECUTE_QUERY_RESULT,
            \App\Model\Message::send(-1, '', '')
        );
    }
}
