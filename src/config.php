<?php

namespace Core;

\define('PROJECT_ROOT_DIR', __DIR__ . DIRECTORY_SEPARATOR . "..");
\define('PATH_TO_IMAGES', PROJECT_ROOT_DIR.DIRECTORY_SEPARATOR."html".DIRECTORY_SEPARATOR."img".DIRECTORY_SEPARATOR);
\define('PATH_TO_ERROR_PATTERNS', PROJECT_ROOT_DIR.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR."View");
\define('EXPECTED_EXECUTE_QUERY_RESULT', 1);
\define('EMAIL_FOR_TESTS', 'Jv9BiGcHwiWlTXd5cxTrrMP@mail.ru');
\define('MAX_TEXT_LENGTH', 255);
\define('NAME_FOR_TESTS', 'TEST_NAME');
\define('PASSWORD_FOR_TESTS', '7t0sZ6UEV+VAR');
\define('HOST_NAME', 'mvc-db-1');
\define('PORT', '3306');
\define('DB_NAME', "mvc");
\define('USER_NAME', "root");
\define('PASSWORD', "1234");
\define('PASSWORD_SALT', "TOZk8ZlfvTIY7wEIAV/p89xORZ");
\define(
    "FIELDS_ERRORS_CORRESPONDS",
    [
        'name' =>
            [
                NAME_INCORRECT_LENGTH_MAX,
                NAME_INCORRECT_LENGTH_MIN,
                NAME_ALREADY_EXIST_REGISTRATION,
                EMPTY_NAME_FORM
            ],
        'email' =>
            [
                EMAIL_INCORRECT_LENGTH_MAX,
                EMAIL_INCORRECT_LENGTH_MIN,
                EMAIL_NOT_EXIST,
                EMAIL_ALREADY_EXIST_REGISTRATION,
                EMPTY_EMAIL_FORM
            ],
        'password' =>
            [
                PASSWORD_INCORRECT_LENGTH_MAX,
                PASSWORD_INCORRECT_LENGTH_MIN,
                PASSWORDS_NOT_MATCHES,
                EMPTY_PASSWORD_FORM
            ]
    ]
);
