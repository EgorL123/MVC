<?php

use Core\Application;
use Core\Validator\Email\EmailValidator;
use Core\Validator\EmptyData\EmptyValidator;
use Core\Validator\Image\ImageValidator;
use Core\Validator\Message\MessageTextValidator;
use Core\Validator\Name\NameValidator;
use Core\Validator\Password\PasswordValidator;
use DI\Container;


include '../vendor/autoload.php';


session_start();

$app = new Application();

$containder = new Container();
$containder->set('User', new \App\Model\User());
$containder->set('EmailValidator', new EmailValidator());
$containder->set('PasswordValidator', new PasswordValidator());
$containder->set('NameValidator', new NameValidator());
$containder->set('MessageTextValidator', new MessageTextValidator());
$containder->set('EmptyFormValidator', new EmptyValidator());
$containder->set('ImageValidator', new ImageValidator());


$app->run
(
    new \Core\Post
    (
        new \Core\Email($_POST['email']),
        new \Core\Name($_POST['name']),
        new \Core\Password($_POST['password']),
        new \Core\Password($_POST['password_repeat']),
        new \Core\BlogMessageText($_POST['text']),
        new \Core\MessageId($_POST['messageId']),
        new \Core\UserId($_POST['userId'])

    ), $_SESSION['id'], new \Core\Twig(PATH_TO_TWIG_PATTERNS), TWIG_PATTERNS_NAMES, $containder);


