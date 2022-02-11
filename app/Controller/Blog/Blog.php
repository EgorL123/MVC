<?php

namespace App\Controller\Blog;

use Core\AbstractController;

abstract class Blog extends AbstractController
{
    private array $errors = [];

    protected string $pathToPattern = "Blog" . DIRECTORY_SEPARATOR;

    /**
     * @return mixed[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
