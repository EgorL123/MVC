<?php

namespace Message;
use App\Model\Message;
use App\Model\User;

class DeleteMessageTest extends \PHPUnit\Framework\TestCase
{
    private $object;
    private $faker;


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
     * Тестирование корректного удаления сообщения
     */
    public function testDelete(): void
    {
        \App\Model\Message::send($this->object->getId(), '', '');

        $lastId = \App\Model\Message::getLastId();

        $this->assertEquals(1, \App\Model\Message::delete($lastId));
        $this->assertTrue(empty(\App\Model\Message::getById($lastId)));
    }

    /**
     * Тестирование некорректного удаления сообщения
     */
    public function testDeleteIncorrect(): void
    {

        $this->assertEquals(0, \App\Model\Message::delete(-1));
    }

    public function tearDown(): void
    {
        Message::deleteAll();
        $this->object->delete();

    }
}
