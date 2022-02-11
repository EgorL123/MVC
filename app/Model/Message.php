<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * @var int
     */
    protected const MAX_MESSAGES_COUNT = 20;

    /**
     * @var string
     */
    public $table = "messages";

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $connection = 'default';

    /**
     * @var string[]
     */
    protected $fillable = ['user_id', 'text', 'image_src'];


    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    /**
     * Удаление сообщения по идентификатору
     */
    public static function deleteById(string $messageId): int
    {
        try {
            return self::destroy($messageId);
        } catch (\Exception $exception) {
            return -1;
        }
    }

    /**
     * Удаление всех сообщений пользователя
     * @return mixed
     */
    public static function deleteAllById(string $userId)
    {
        try {
            return self::query()->where('user_id', '=', $userId)->delete();
        } catch (\Exception $exception) {
            return -1;
        }
    }

    public static function deleteAll(): int
    {
        return self::query()->delete();
    }

    /**
     * Получение списка всех сообщений
     * @return mixed[]
     */
    public function getAll(): array
    {

        $messages = self::with('users')->limit(self::MAX_MESSAGES_COUNT)
            ->orderBy('id', 'DESC')->get()->toArray();

        foreach (array_keys($messages) as $key) {
            $messages[$key]['name'] = $messages[$key]['users']['name'];
        }


        return $messages;
    }

    public function getById(string $id)
    {
        try {
            return self::query()->where('user_id', '=', $id)->get()->toArray();
        } catch (\Exception $exception) {
            return -1;
        }
    }

    /**
     * Получение идентификатора последнего сообщения
     */
    public static function getLastId(): ?int
    {

        $arr = self::query()->orderBy('id', 'DESK')->get()->toArray();
        return $arr[0]['id'];
    }

    /**
     * Получение сообщений пользователя по id пользователя
     */
    public static function getAllByUserIdJSON(string $userId): ?string
    {
        try {
            $messages = self::query()->where('user_id', '=', $userId)->get()->toArray();
        } catch (\Exception $exception) {
            return null;
        }

        return json_encode($messages);
    }

    /**
     * Отправка сообщения
     */
    public function send(string $userId, string $text, ?string $imgExtension): ?int
    {
        $imgSrc = empty($imgExtension) ? null : (self::getLastId() + 1) . "." . $imgExtension;

        try {
            $this->fill(['user_id' => $userId, 'text' => $text, 'image_src' => $imgSrc])->save();
            return $this->id;
        } catch (\Exception $exception) {
            return -1;
        }
    }
}
