<?php

namespace App\Controller\User;

use Core\IPost;

class UserAuth extends User
{
    public array $errors;
/**
     * Контроллер авторизации
     * При пустом массиве $_POST перенаправит на страницу index, в которой отобразится форма авторизации
     * Если массив $_POST не пустой, проверяет корректность заполнения полей и авторизует пользователя
     * @throws \Core\RedirectException
     */
    public function authorizationAction(?IPost $post, ?string $userId, array $patternNames): ?string
    {
        $emptyValidator = $this->container->get('EmptyFormValidator');
        $data =
            [
                'email' => $post->getEmail()->get(),
                'name' => $post->getName()->get(),
                'password' => $post->getPassword()->get(),
            ];
        $fieldsErrors =
            [
                'name' => EMPTY_NAME_FORM,
                'email' => EMPTY_EMAIL_FORM,
                'password' => EMPTY_PASSWORD_FORM
            ];
        $name = $data['name'];
        $password = $data['password'];
        $email = $data['email'];
        $redirect =
            empty($post->getEmail()->get()) &&
            empty($post->getPassword()->get()) &&
            empty($post->getName()->get());
        if ($redirect) {
            $this->redirect('/public_html/user/index');
        } else {
            $notEmptyFields = $emptyValidator->validateFormNotEmpty($data, ['password_repeat']);
            $user = $this->container->get('User');
            $user->setPassword($password);
            $user->setEmail($email);
            $user->setName($name);
            $dbUser = $user->getByEmail($post->getEmail()->get());
            if (count($notEmptyFields) != count($fieldsErrors)) {
                return $this->getPattern(['errCodes' => array_diff_key($fieldsErrors, array_flip($notEmptyFields))], $patternNames['authFail']);
            }


            if (empty($dbUser)) {
                $this->errors[] = EMAIL_NOT_EXIST;
                return $this->getPattern(['errCodes' => [EMAIL_NOT_EXIST]], $patternNames['authFail']);
            }

            if ($user->getName() !== $dbUser->getName()) {
                $this->errors[] = NAME_NOT_EXIST;
                return $this->getPattern(['errCodes' => [NAME_NOT_EXIST]], $patternNames['authFail']);
            }


            if ($user->getHash($user->getPassword()) !== $dbUser->getPassword()) {
                $this->errors[] = PASSWORDS_NOT_MATCHES;
                return $this->getPattern(['errCodes' => [PASSWORDS_NOT_MATCHES]], $patternNames['authFail']);
            }


            $_SESSION['id'] = $dbUser->getId();
            $this->redirect("/public_html/blog");
        }
    }
}
