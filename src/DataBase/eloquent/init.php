<?php

namespace Core;

include "../../../../vendor/autoload.php";

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule();
const CONNECTION_DAFAULT = 'default';

$capsule->addConnection(
    [
    'driver' => 'mysql',
    'host' => HOST_NAME,
    'database' => DB_NAME,
    'username' => USER_NAME,
    'password' => PASSWORD,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => ''
    ]
);

$capsule->setAsGlobal();

$capsule->bootEloquent();
$capsule->getConnection(CONNECTION_DAFAULT);

class User extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $connection = CONNECTION_DAFAULT;

    public function posts()
    {
        return $this->hasMany(Message::class, 'user_id', 'id');
    }
}

class Message extends \Illuminate\Database\Eloquent\Model
{
    protected $table = "messages";
    protected $primaryKey = "id";
    protected $connection = CONNECTION_DAFAULT;

    public function posts()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
