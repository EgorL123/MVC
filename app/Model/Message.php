<?php

namespace App\Model;

use Core\AbstractModel;
use Core\DataBase;

class Message extends AbstractModel
{
    private const MAX_MESSAGES_COUNT = 20;

    /**
     * @param $id
     * @param $userId
     * @param $text
     * @param $date
     */
    public function __construct()
    {
    }

    /**
     * Сохранение сообщения в БД.
     * @param $imgExtension
     */
    public static function send(string $userId, string $text, $imgExtension): int
    {
        $pdo = DataBase::getInstance();
        $sql = "INSERT INTO messages(`user_id`, `text`, `image_src`) VALUES(:userId, :text, :image_src)";

        $imageLink = empty($imgExtension) ? null : (self::getLastId() + 1) . "." . $imgExtension;

        return $pdo->exec($sql, ['userId' => $userId, 'text' => $text,'image_src' => $imageLink]);
    }

    /**
     * Получение списка всех сообщений
     * @return mixed[]
     */
    public static function getAll(): array
    {
        $pdo = DataBase::getInstance();
        $sql = "SELECT messages.*, users.name FROM messages INNER JOIN users ON messages.user_id = users.id ORDER BY messages.id DESC LIMIT " . self::MAX_MESSAGES_COUNT;

        return $pdo->fetchAll($sql, []);
    }


    /**
     * Получение идентификатора последнего сообщения
     */
    public static function getLastId(): int
    {
        $pdo = DataBase::getInstance();
        $sql = "SELECT id FROM messages ORDER BY id DESC";

        return $pdo->fetchAll($sql, [])[0]['id'];
    }


    /**
     * Удаление сообщения по идентификатору
     * @return void
     */
    public static function delete(string $id): int
    {
        $pdo = DataBase::getInstance();
        $sql = "DELETE FROM messages WHERE id = :id";

        return $pdo->exec($sql, ['id' => $id]);
    }


    /**
     * Получение сообщений пользователя по id пользователя
     */
    public static function getAllByUserIdJSON(string $userId): string
    {
        $pdo = DataBase::getInstance();
        $sql = "SELECT * FROM messages WHERE user_id = :id LIMIT " . self::MAX_MESSAGES_COUNT;

        return json_encode($pdo->fetchAll($sql, ['id' => $userId]));
    }
}
