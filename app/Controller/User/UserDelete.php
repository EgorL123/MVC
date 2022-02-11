<?php

namespace App\Controller\User;

use App\Model\Message;
use Core\IPost;

class UserDelete extends User
{
    /**
     * @var int[]|mixed
     */
    public $errors;
    /**
     * Удаление пользователя по идентификатору(для администратора)
     */
    public function deleteAction(IPost $post, string $userId, array $patternNames): string
    {
        $user = $this->container->get('User');

        if (in_array($userId, ADMINS)) {
            if (Message::deleteAllById($post->getUserId()->get()) != -1) {
                if (($count = $user->deleteById($post->getUserId()->get())) != -1) {
                    return $this->getPattern(['affectedRows' => $count], $patternNames['AdminActionOk']);
                } else {
                    return $this->getPattern(['errCodes' => USER_DELETE_ERROR], $patternNames['AdminActionFail']);
                }
            } else {
                return $this->getPattern(['errCodes' => USER_MESSAGES_DELETE_ERROR], $patternNames['AdminActionFail']);
            }
        }

        $this->errors[] = NOT_ADMIN_ACCESS;

        return $this->getPattern(['errCodes' => $this->errors], $patternNames['AdminActionFail']);
    }
}
