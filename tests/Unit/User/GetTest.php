<?php

class GetTest extends \PHPUnit\Framework\TestCase
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
}
