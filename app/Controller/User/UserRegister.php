<?php

namespace App\Controller\User;

use App\Controller\User\Validation\UserRegisterValidate;
use Core\IPost;

class UserRegister extends User
{
    protected \App\Model\User $user;
    protected ?int $currentUserId = null;
    public array $fieldsErrors =
        [
            'name' => EMPTY_NAME_FORM,
            'email' => EMPTY_EMAIL_FORM,
            'password' => EMPTY_PASSWORD_FORM
        ];
    public array $patternNames;
    public ?string $name = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $passwordRepeat = null;
/**
     * @throws \Core\RedirectException
     * @param int|null $userId
     * @param mixed[] $patternNames
     */
    public function registerAction(?IPost $post, ?string $userId, array $patternNames): ?string
    {
        $this->patternNames = $patternNames;
        $this->currentUserId = $userId;
        $data =
            [
                'email' => $post->getEmail()->get(),
                'name' => $post->getName()->get(),
                'password' => $post->getPassword()->get(),
                'password_repeat' => $post->getRepeatPassword()->get()
            ];
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->password = $data['password'] ?? null;
        $this->passwordRepeat = $data['password_repeat'] ?? null;
        $userRegisterValidate = new UserRegisterValidate();
        $redirect = true;
        foreach ($data as $singleData) {
            if (!empty($singleData)) {
                $redirect = false;
            }
        }

        if ($redirect) {
            return $this->getPattern([], $patternNames['registerPage']);
        }

        $this->user = $this->container->get('User');
        $emailValidator = $this->container->get('EmailValidator');
        $passwordValidator = $this->container->get('PasswordValidator');
        $nameValidator = $this->container->get('NameValidator');
        $emptyValidator = $this->container->get('EmptyFormValidator');
        $validators =
            [
                'email' => $emailValidator,
                'password' => $passwordValidator,
                'name' => $nameValidator,
                'empty' => $emptyValidator
            ];
        $result = $userRegisterValidate->validate($validators, $data, $this);
        if (is_string($result)) {
            return $result;
        } else {
            $this->user->setName($this->name);
            $this->user->setEmail($this->email);
            $this->user->setPassword($this->password);
            $this->user->send();
            if (!in_array($userId, ADMINS)) {
                $this->redirect('/public_html/user/authorization');
            } else {
                return $this->getPattern([], $patternNames['AdminActionOk']);
            }
        }
    }
}
