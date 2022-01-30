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
    private string $createDate;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCreateDate(): string
    {
        return $this->createDate;
    }

    /**
     * @param $id
     * @param $password
     * @param $name
     * @param $email
     */
    public function __construct(string $id, string $password, string $name, string $email, string $date)
    {
        $this->id = $id;
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
        $this->createDate = $date;
    }

    /**
     * Сохранение модели пользователя в базу данных
     * Возвращает число измененных строк либо выкидывает исключение
     * @return void
     */
    public function save(): int
    {
        $pdo = DataBase::getInstance();
        $sql = "INSERT INTO users(`email`, `password`, `name`) VALUES (:email, :password, :name)";

        return $pdo->exec($sql, ['email' => $this->email, 'password' => self::getHash($this->password), 'name' => $this->name]);
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

        if ($result = $pdo->fetchAll($sql, ['name' => $name])) {
            $data = $result[0];
            return new self($data['id'], $data['password'], $data['name'], $data['email'], $data['create_date']);
        }

        return null;
    }

    /**
     * Получение модели пользователя по email
     * @param string $name
     * @return array
     */
    public static function getByEmail(string $email): ?User
    {
        $pdo = DataBase::getInstance();
        $sql = "SELECT * FROM users WHERE email = :email";

        if ($result = $pdo->fetchAll($sql, ['email' => $email])) {
            $data = $result[0];
            return new self($data['id'], $data['password'], $data['name'], $data['email'], $data['create_date']);
        }

        return null;
    }


    /**
     * Проверка правильности пароля пользователя
     */
    public static function checkPassword(string $name, string $password): bool
    {
        $pdo = DataBase::getInstance();
        $sql = "SELECT * FROM users WHERE name = :name";
        return ($result = $pdo->fetchAll($sql, ['name' => $name])) && $result[0]['password'] == self::getHash($password);
    }

    /**
     * Получение захэшированного пароля с солью
     */
    public static function getHash(string $password): string
    {
        return sha1($password . PASSWORD_SALT);
    }
}
