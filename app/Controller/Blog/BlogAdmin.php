<?php

namespace App\Controller\Blog;

use Core\IPost;

class BlogAdmin extends Blog
{
    public array $errors;
/**
     * @return string|void
     */
    public function adminAction(IPost $post, ?string $userId, array $patternNames)
    {
        if (in_array($userId, ADMINS) && isset($userId)) {
            return $this->getPattern([], $patternNames['adminPage']);
        }

        $this->errors[] = NOT_ADMIN_ACCESS;
    }
}
