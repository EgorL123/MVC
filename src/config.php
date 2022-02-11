<?php

namespace Core;

define('PROJECT_ROOT_DIR', __DIR__ . "/.." . DIRECTORY_SEPARATOR);
define('PATH_TO_IMAGES', PROJECT_ROOT_DIR . "public_html" . DIRECTORY_SEPARATOR . "img" . DIRECTORY_SEPARATOR);
define('PATH_TO_ERROR_PATTERNS', PROJECT_ROOT_DIR . "app" . DIRECTORY_SEPARATOR . "View");
define('EXPECTED_EXECUTE_QUERY_RESULT', 1);
define('EMAIL_FOR_TESTS', 'Jv9BiGcHwiWlTXd5cxTrrMP@mail.ru');
define('MAX_TEXT_LENGTH', 255);
define('NAME_FOR_TESTS', 'TEST_NAME');
define('PASSWORD_FOR_TESTS', '7t0sZ6UEV+VAR');
define('HOST_NAME', 'mvc-db-1');
define('PORT', '3306');
define('DB_NAME', "mvc");
define('USER_NAME', "root");
define('PASSWORD', "1234");
define('PASSWORD_SALT', "TOZk8ZlfvTIY7wEIAV/p89xORZ");
define('CONNECTION_DEFAULT', 'default');

define(
    'TWIG_PATTERNS_NAMES',
    [
        'adminPage' => 'adminPage.twig',
        'BlogMainPage' => 'Blog.twig',
        'BlogMainErr' => 'blogErr.twig',
        'sendMessageErr' => 'sendErr.twig',
        'AdminActionOk' => 'AdminActionSuccess.twig',
        'AdminActionFail' => 'AdminActionNotSuccess.twig',
        'authPage' => 'authorization.twig',
        'authFail' => 'authorizationErr.twig',
        'registerPage' => 'register.twig',
        'registerFail' => 'registerErr.twig'

    ]
);

define(
    'PATH_TO_TWIG_PATTERNS',
    PROJECT_ROOT_DIR .
    "Resource" . DIRECTORY_SEPARATOR .
    "View" . DIRECTORY_SEPARATOR .
    "Twig"
);
