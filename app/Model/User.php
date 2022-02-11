<?php

namespace App\Model;

use App\Model\Message;
use Core\DataBase;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    private ?string $id;

    private ?string $password;

    private ?string $name;

    private ?string $email;

    /**
     * @var mixed[]|null
     */
    public $table = "users";

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
    protected $fillable = ['password', 'name', 'email'];

    /**
     * @param array|null $table
     * @param string|null $primaryKey
     * @param string|null $connection
     * @param array|null $fillable
     * @param string|null $id
     * @param string|null $password
     * @param string|null $name
     * @param string|null $email
     */
    public function __construct(
        array $table = null,
        string $primaryKey = null,
        string $connection = null,
        array $fillable = null,
        string $id = null,
        string $password = null,
        string $name = null,
        string $email = null
    ) {
        $this->id = $id;
        $this->password = $password;
        $this->name = $name;
        $this->email = $email;
        $this->table = $table;
    }


    public function messages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }


    /**
     * @return $this
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Получение захэшированного пароля с солью
     */
    public function getHash(string $password): string
    {
        return sha1($password . PASSWORD_SALT);
    }


    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Получение модели пользователя по идентификатору
     * @return User|null
     */
    public function get(string $userId)
    {
        try {
            $data = (self::query()->where('id', '=', $userId)->get()->toArray())[0];

            if (!empty($data)) {
                return new self(
                    null,
                    null,
                    null,
                    null,
                    $data['id'],
                    $data['password'],
                    $data['name'],
                    $data['email']
                );
            }
        } catch (\Exception $exception) {
            return null;
        }

        return null;
    }

    /**
     * Получение модели пользователя по имени
     * @return User|null
     */
    public function getByName(string $name)
    {

        try {
            $data = (self::query()->where('name', '=', $name)->get()->toArray())[0];

            if (!empty($data)) {
                return new self(
                    null,
                    null,
                    null,
                    null,
                    $data['id'],
                    $data['password'],
                    $data['name'],
                    $data['email'],
                );
            }
        } catch (\Exception $exception) {
            return null;
        }

        return null;
    }

    /**
     * Получение модели пользователя по email
     * @return User|null
     */
    public function getByEmail(string $email)
    {
        $data = (self::query()->where('email', '=', $email)->get()->toArray())[0];

        if (!empty($data)) {
            return new self(
                null,
                null,
                null,
                null,
                $data['id'],
                $data['password'],
                $data['name'],
                $data['email']
            );
        }

        return null;
    }

    /**
     * Удаление модели пользователя по идентификатору
     * @return int
     */
    public function delete(): ?int
    {
        try {
            return self::destroy($this->id);
        } catch (\Exception $exception) {
            return -1;
        }
    }

    /**
     * Удаление пользователя по идентификатору
     */
    public function deleteById(string $id): int
    {
        try {
            return self::query()->where('id', '=', $id)->delete();
        } catch (\Exception $exception) {
            return -1;
        }
    }

    /**
     * Сохранение модели пользователя в базу данных
     */
    public function send(bool $encrypt = true): int
    {
        $password = $this->password;

        if ($encrypt) {
            $password = self::getHash($this->password);
        }

        try {
            return $this->fill(['name' => $this->name, 'email' => $this->email, 'password' => $password])->save();
        } catch (\Exception $exception) {
            return -1;
        }
    }

    /**
     * Изменение данных пользователя
     */
    public function change(): int
    {
        return self::query()->where('id', '=', $this->id)
            ->update(
                [
                    'email' => $this->email,
                    'name' => $this->name,
                    'password' => $this->password
                ]
            );
    }
}
