<?php

namespace Message;
use App\Model\Message;
use const EXPECTED_EXECUTE_QUERY_RESULT;

class SendMessageTest extends \PHPUnit\Framework\TestCase
{
    private $object;
    private $faker;

    /**
     * @codeCoverageIgnore
     */
    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create('ru_RU');

        $data =
            [
                'password' => $this->faker->password,
                'name'=>$this->faker->firstName,
                'email'=>$this->faker->email
            ];


        \Core\Normalizer::normalizeSpecialChars($data);
        foreach ($data as $key => $value)
        {
            $data[$key] = \Core\Normalizer::normalizeSpaces($value);
        }

        $this->object = new \App\Model\User('', $data['password'], $data['name'], $data['email'], '');
        $this->object->save();
        $this->object->setId(\App\Model\User::getByEmail($this->object->getEmail())->getId());
    }

    /**
     * Тестирование отправки корректного сообщения
     */
    public function testSend(): void
    {
        \App\Model\Message::send($this->object->getId(), $this->faker->text, '');
        $lastId = Message::getLastId();

        $this->assertTrue(!empty(Message::getById($lastId)));

    }

    /**
     * Тестирование отправки сообщения с некорректным идентификатором пользователя
     */
    public function testIncorrectSend(): void
    {
        $this->assertEquals(0, \App\Model\Message::send(-1, '', ''));
    }

    public function tearDown(): void
    {
        Message::deleteAll();
        $this->object->delete();
    }
}
