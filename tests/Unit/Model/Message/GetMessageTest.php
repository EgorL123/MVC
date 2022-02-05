<?php

namespace Message;

use App\Model\Message;
use App\Model\User;
use Faker\Factory;
use PHPUnit\Framework\TestCase;

class GetMessageTest extends TestCase
{
    private $user;
    private $faker;

    public function setUp(): void
    {
        $this->faker = Factory::create('ru_RU');
        $this->user = new User('', $this->faker->password, $this->faker->firstName, $this->faker->email, '');
        $this->user->save();
        $this->user->setId(User::getByEmail($this->user->getEmail())->getId());

    }

    /**
     * Проверка количества выводимых сообщений
     */
    public function testGetMaxCount(): void
    {
        for($i = 0; $i < 40; $i++) {
            Message::send($this->user->getId(), $this->faker->text,'');
        }

        $this->assertTrue(count(Message::getAll()) <= 20);
    }

    public function testGetJSON(): void
    {
        for($i = 0; $i < 40; $i++) {
            Message::send($this->user->getId(), $this->faker->text,'');
        }

        $this->assertTrue(count(json_decode(Message::getAllByUserIdJSON($this->user->getId()))) == 20);
    }


    public function tearDown(): void
    {
        Message::deleteAll();
        $this->user->delete();
    }


}