<?php

class DeleteMessageTest extends \PHPUnit\Framework\TestCase
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
     * Тестирование корректного удаления сообщения
     */
    public function testDelete(): void
    {
        $this->assertEquals(
            EXPECTED_EXECUTE_QUERY_RESULT,
            \App\Model\Message::send($this->object->getId(), '', '')
        );
        $lastId = \App\Model\Message::getLastId();

        $this->assertEquals(EXPECTED_EXECUTE_QUERY_RESULT, \App\Model\Message::delete($lastId));
    }

    /**
     * Тестирование некорректного удаления сообщения
     */
    public function testDeleteIncorrect(): void
    {
        $this->expectException(Exception::class);
        $this->assertEquals(
            EXPECTED_EXECUTE_QUERY_RESULT,
            \App\Model\Message::send(-1, '', '')
        );
        $lastId = \App\Model\Message::getLastId();
        \App\Model\Message::delete($lastId);
    }
}
