<?php

namespace App\Controller\Blog;

use Core\IPost;

class BlogIndex extends Blog
{
    /**
     * @var int[]|mixed
     */
    public $errors;
/**
     * В случае, если пользователь не авторизован, перенаправит на страницу авторизации,
     * в противном случае запускается контроллер вывода соообщений
     * @throws \Core\RedirectException
     */
    public function indexAction(?IPost $post, ?string $userId, array $patternNames)
    {
        if (!isset($userId)) {
            $this->errors[] = USER_NOT_AUTHORIZED;
            return $this->getPattern(['errCodes' => $this->errors], $patternNames['BlogMainErr']);
        }

        return $this->redirect('/public_html/blog/getAll');
    }
}
