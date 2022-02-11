<?php

namespace App\Controller\Blog;

use App\Model\Message;
use Core\IPost;

class BlogDelete extends Blog
{
    public array $errors;
/**
     * Удаление сообщения (для администратора)
     * @throws \Core\RedirectException
     */
    public function deleteAction(?IPost $post, ?string $userId): void
    {
        if (in_array($userId, ADMINS) && isset($userId)) {
            Message::deleteById($post->getMessageId()->get());
            $this->redirect('/public_html/blog');
        }

        $this->errors[] = NOT_ADMIN_ACCESS;
    }
}
