<?php

use App\Controller\User;
use Core\Application;

include "../src/config.php";
include "../src/Errors.php";
include "../src/Admins.php";
include '../vendor/autoload.php';

$app = new Application();


$app->run();
