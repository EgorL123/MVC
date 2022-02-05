<?php

namespace User;
use App\Model\User;

class GetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Faker\Generator|mixed
     */
    public $faker;
    private $object;


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
     * Проверка получения пользователя по email
     */
    public function testGetByEmail(): void
    {
        $user = \App\Model\User::getByEmail($this->object->getEmail());

        $this->assertEquals($this->object->getEmail(), $user->getEmail());
        $this->assertEquals($this->object->getName(), $user->getName());
        $this->assertEquals(\App\Model\User::getHash($this->object->getPassword()), $user->getPassword());
    }

    /**
     * Проверка получения пользователя по имени
     */
    public function testGetByName(): void
    {
        $user = \App\Model\User::getByName($this->object->getName());

        $this->assertEquals($this->object->getEmail(), $user->getEmail());
        $this->assertEquals($this->object->getName(), $user->getName());
        $this->assertEquals(\App\Model\User::getHash($this->object->getPassword()), $user->getPassword());
    }

    /**
     * Проверка получения пользователя по Id
     */
    public function testGetById(): void
    {
        $user = \App\Model\User::getByName($this->object->getName());
        $user = \App\Model\User::get($user->getId());

        $this->assertEquals($this->object->getEmail(), $user->getEmail());
        $this->assertEquals($this->object->getName(), $user->getName());
        $this->assertEquals(\App\Model\User::getHash($this->object->getPassword()), $user->getPassword());
    }

    /**
     * Проверка сравнения паролей
     */
    public function testCheckPassword(): void
    {
        $user = \App\Model\User::getByName($this->object->getName());
        $this->assertEquals(\App\Model\User::getHash($this->object->getPassword()), $user->getPassword());
    }

    public function testIncorrectGet(): void
    {
        $this->assertEquals(null, User::get(-1));

    }


    public function tearDown(): void
    {
        $this->object->delete();
    }
}
