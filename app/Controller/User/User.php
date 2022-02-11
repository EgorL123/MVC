<?php

namespace App\Controller\User;

use Core\AbstractController;
use Core\DataBase;

abstract class User extends AbstractController
{
    private array $errors = [];

    protected string $pathToPattern = "User" . DIRECTORY_SEPARATOR;

    /**
     * @return mixed[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
