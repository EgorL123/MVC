<?php

namespace Core\DataBase\eloquent\migrations;

include __DIR__ . "/../init.php";

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

\Illuminate\Database\Capsule\Manager::schema()->create('users', function (\Illuminate\Database\Schema\Blueprint $table): void {
    $table->increments('id');
    $table->string('email');
    $table->string('password');
    $table->string('name');
    $table->timestamp('create_date');
});

\Illuminate\Database\Capsule\Manager::schema()->create('messages', function (\Illuminate\Database\Schema\Blueprint $table): void {
    $table->increments('id');
    $table->integer('user_id');
    $table->foreign('user_id')->references('id')->on('users');
    $table->text('text');
    $table->string('image_src');
    $table->timestamp('created_date')->useCurrent();
});
