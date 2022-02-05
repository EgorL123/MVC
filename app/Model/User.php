<?php

namespace App\Model;

use Core\AbstractModel;
use Core\DataBase;

class User extends AbstractModel
{
    private string $id;
    private string $password;
    private string $name;
    private string $email;

    public function getId(): string
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }


    public function setId(string $id) : void
    {
        $this->id = $id;
    }

    /**
     * @param $id
     * @param $password
     * @param $name
     * @param $email
     */
    public function __construct(string $id, string $password, string $name, string $email)
    {
        $this->id = $id;
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Сохранение модели пользователя в базу данных
     * Возвращает число измененных строк либо выкидывает исключение
     * @return void
     */
    public function save(bool $encrypt = true): int
    {
        $pdo = DataBase::getInstance();
        $sql = "INSERT INTO users(`email`, `password`, `name`) VALUES (:email, :password, :name)";

        $password = $this->password;

        if($encrypt) {
            $password = self::getHash($password);
        }

        return $pdo->exec($sql, ['email' => $this->email, 'password' => $password, 'name' => $this->name]);
    }

    /**
     * Получение модели пользователя по идентификатору
     * @return array
     */
    public static function get(string $userId): ?User
    {
        $pdo = DataBase::getInstance();
        $sql = "SELECT * FROM users WHERE id = :id";

        if ($result = $pdo->fetchAll($sql, ['id' => $userId])) {
            $data = $result[0];
            return new self($data['id'], $data['password'], $data['name'], $data['email'], $data['create_date']);
        }

        return null;
    }

    /**
     * Получение модели пользователя по имени
     * @return array
     */
    public static function getByName(string $name): ?User
    {
        $pdo = DataBase::getInstance();
        $sql = "SELECT * FROM users WHERE name = :name";

        if (!empty($result = $pdo->fetchAll($sql, ['name' => $name]))) {
            $data = $result[0];
            return new self($data['id'], $data['password'], $data['name'], $data['email'], $data['create_date']);
        }

        return null;
    }

    /**
     * Получение модели пользователя по email
     * @return array
     */
    public static function getByEmail(string $email): ?User
    {
        $pdo = DataBase::getInstance();
        $sql = "SELECT * FROM users WHERE email = :email";


        if (!empty($result = $pdo->fetchAll($sql, ['email' => $email]))) {
            $data = $result[0];
            return new self($data['id'], $data['password'], $data['name'], $data['email'], $data['create_date']);
        }

        return null;
    }




    /**
     * Получение захэшированного пароля с солью
     */
    public static function getHash(string $password): string
    {
        return sha1($password . PASSWORD_SALT);
    }

    /**
     * @throws \Exception
     */
    public function delete(): bool
    {
        $pdo = DataBase::getInstance();
        $sql = "DELETE FROM users WHERE id = {$this->getId()}";

        return $pdo->exec($sql,[]);
    }


}
