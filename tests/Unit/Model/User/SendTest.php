<?php

namespace User;
use App\Model\User;

class SendTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Faker\Generator|mixed
     */
    public $faker;
    private $object;

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


        $this->object = new \App\Model\User('', $data['password'], $data['name'], $data['email'], '');
        $this->object->save();
        $this->object->setId(\App\Model\User::getByEmail($this->object->getEmail())->getId());
    }

    /**
     * Проверка отправки пользователя
     */
    public function testSend(): void
    {
        $this->assertEquals($this->object->getEmail(), User::getByEmail($this->object->getEmail())->getEmail());
    }

    public function tearDown(): void
    {
        $this->object->delete();
    }
}
