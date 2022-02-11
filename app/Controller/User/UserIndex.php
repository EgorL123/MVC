<?php

namespace App\Controller\User;

use Core\IPost;

class UserIndex extends User
{
    /**
     * @var int[]|mixed
     */
    public $errors;
/**
     * @throws \Core\RedirectException
     */
    public function indexAction(?IPost $post, ?string $userId, array $patternsNames): ?string
    {
        if (!isset($userId)) {
            $this->errors[] = USER_NOT_AUTHORIZED;
            return $this->getPattern($this->errors, $patternsNames['authPage']);
        } else {
            $this->redirect('/public_html/blog');
        }
    }
}
