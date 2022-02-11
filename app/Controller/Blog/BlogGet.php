<?php

namespace App\Controller\Blog;

use App\Model\Message;
use Core\IPost;

class BlogGet extends Blog
{
    /**
     * @var int[]|mixed
     */
    public $errors;
/**
     * Вывод всех сообщений на страницу блога
     */
    public function getAllAction(?IPost $post, ?string $userId, array $patternNames): string
    {
        if (!isset($userId)) {
            $this->errors[] = USER_NOT_AUTHORIZED;
            return $this->getPattern(['errCodes' => $this->errors], $patternNames['BlogMainErr']);
        }

        $message = new Message();
        $messages = $message->getAll();
        return $this->getPattern([
                'messages' => $messages, 'pathToImages' => "../../public_html/img/", 'userId' => $userId,
                'admins' => ADMINS
            ], $patternNames['BlogMainPage']);
    }
}
