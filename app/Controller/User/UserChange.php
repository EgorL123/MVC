<?php

namespace App\Controller\User;

use App\Model\Message;
use Core\IPost;

class UserChange extends User
{
    /**
     * Контроллер изменения данных пользователя
     * @param $container
     * @return void
     */
    public function changeAction(IPost $post, string $userId, array $patternNames): string
    {
        $id = (int) $post->getUserId()->get();
        $name = $post->getName()->get();
        $email = $post->getEmail()->get();
        $password = $post->getPassword()->get();

        if (empty($id) || empty($name) || empty($email) || empty($password)) {
            return $this->getPattern(['errCodes' => [EMPTY_FIELDS]], $patternNames['AdminActionFail']);
        }

        $user = new \App\Model\User();

        if (!empty($user->get($id))) {
            if (empty($user->getByEmail($email))) {
                if (empty($user->getName())) {
                    $user->setPassword($password);
                    $user->setEmail($email);
                    $user->setName($name);
                    $user->setName($name);
                    $user->setId(sha1($id));
                    $user->change();

                    return $this->getPattern([], $patternNames['AdminActionOk']);
                } else {
                    return
                        $this->getPattern(['errCodes' => [NAME_ALREADY_EXIST_STRICT]], $patternNames['AdminActionFail']);
                }
            } else {
                return
                    $this->getPattern(['errCodes' => [EMAIL_ALREADY_EXIST_STRICT]], $patternNames['AdminActionFail']);
            }
        } else {
            return
                $this->getPattern(['errCodes' => [USER_NOT_EXIST_STRICT]], $patternNames['AdminActionFail']);
        }
    }
}
