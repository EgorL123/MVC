<?php

namespace App\Controller;

use Core\AbstractController;
use Core\DataBase;
use Core\Normalizer;
use Core\Validator;
use PharIo\Manifest\ElementCollection;
use PhpParser\Node\Expr\PreInc;

class User extends AbstractController
{

    private array $errors = [];
    private string $pathToPatterns = PATH_TO_ERROR_PATTERNS.DIRECTORY_SEPARATOR."User";

    /**
     * @throws \Core\RedirectException
     */
    public function indexAction(bool $print = true): void
    {

        if(!$print)
        {
            ob_start();
        }

        if (!isset($_SESSION['id'])) {
            $this->errors[] = USER_NOT_AUTHORIZED;
            echo $this->getView()->render($this->pathToPatterns, [], 'authorization.twig',0,0,0);
        } else {
            $this->redirect('/html/blog');
        }
    }


    /**
     * Контроллер авторизации
     * При пустом массиве $_POST перенаправит на страницу index, в которой отобразится форма авторизации
     * Если массив $_POST не пустой, проверяет корректность заполнения полей и авторизует пользователя
     * @throws \Core\RedirectException
     */
    public function authorizationAction(bool $print = true): void
    {
        if(!$print)
        {
            ob_start();
        }

        Normalizer::normalizeSpecialChars($_POST);
        foreach ($_POST as $key => $item) {
            $_POST[$key] = Normalizer::normalizeSpaces($item);
        }

        $fieldsErrors =
            [
                'name' => EMPTY_NAME_FORM,
                'email' => EMPTY_EMAIL_FORM,
                'password' => EMPTY_PASSWORD_FORM
            ];
        $name = $_POST['name'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $flag = 1;
        $validator = new Validator();

        if (!empty($_POST)) {
            $flag = 0;
        }

        if ($flag) {
            $this->redirect('/html/user/index');
        } else {
            $notEmptyFields = $validator->validateFormNotEmpty($_POST, ['password_repeat']);
            $user = new \App\Model\User('',$password, $name, $email,'');

            $dbUser = \App\Model\User::getByEmail($email);

            if (count($notEmptyFields) != count($fieldsErrors)) {

                echo $this->getView()->render($this->pathToPatterns
                    , ['errCodes' => array_diff_key($fieldsErrors, array_flip($notEmptyFields))],
                    'authorizationErr.twig',0,0,0);
                return;
            }


            if (empty($dbUser)) {
                $this->errors[] = EMAIL_NOT_EXIST;
               echo $this->getView()->render($this->pathToPatterns, ['errCodes' => [EMAIL_NOT_EXIST]], 'authorizationErr.twig',0,0,0);


               return;
           }

            if ($user->getName() !== $dbUser->getName()) {
                $this->errors[] = NAME_NOT_EXIST;

                echo $this->getView()->render($this->pathToPatterns, ['errCodes' => [NAME_NOT_EXIST]], 'authorizationErr.twig',0,0,0);

                return;
            }


            if (\App\Model\User::getHash($user->getPassword()) !== $dbUser->getPassword()) {
                $this->errors[] = PASSWORDS_NOT_MATCHES;
                echo $this->getView()->render($this->pathToPatterns, ['errCodes' => [PASSWORDS_NOT_MATCHES]], 'authorizationErr.twig',0,0,0);
                return;
            }


            $_SESSION['id'] = $dbUser->getId();

            $this->redirect('/html/blog');
        }


    }


    /**
     * Контроллер регистрации
     * Если массив $_POST пуст, отобразится страница с регистрацией
     * в противном случае происходит валидация данных из массива $_POST
     * и в случае успеха регистрация пользователя
     * @throws \Core\RedirectException
     */
    public function registerAction(bool $print = true): void
    {
        if(!$print)
        {
            ob_start();
        }

        Normalizer::normalizeSpecialChars($_POST);
        foreach ($_POST as $key => $item) {
            $_POST[$key] = Normalizer::normalizeSpaces($item);
        }

        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $passwordRepeat = $_POST['password_repeat'] ?? null;
        $flag = 0;
        $fieldsErrors =
            [
                'name' => EMPTY_NAME_FORM,
                'email' => EMPTY_EMAIL_FORM,
                'password' => EMPTY_PASSWORD_FORM
            ];
        foreach ($_POST as $value) {
            if (!empty($value)) {
                $flag = 1;
            }
        }

        if ($flag) {
            $validator = new Validator();
            $notEmptyFields = $validator->validateFormNotEmpty($_POST, ['password_repeat']);

            if (count($notEmptyFields) != count($fieldsErrors)) {

                echo $this->getView()->render($this->pathToPatterns
                    , ['errCodes' => array_diff_key($fieldsErrors, array_flip($notEmptyFields))],
                    'registerErr.twig',0,0,0);
                return;
            }

            if (\App\Model\User::getByName($name) !== null) {

                $this->errors[] = NAME_ALREADY_EXIST_REGISTRATION;
                echo $this->getView()->render($this->pathToPatterns, ['errCodes' => $this->errors], 'registerErr.twig',0,0,0);

                return;
            }

            if (\App\Model\User::getByEmail($email) !== null) {
                $this->errors[] = EMAIL_ALREADY_EXIST_REGISTRATION;
                echo $this->getView()->render($this->pathToPatterns, ['errCodes' => $this->errors], 'registerErr.twig',0,0,0);


                return;
            }


            if (!empty($errors = $validator->validateEmail($email))) {
                $this->errors[] = $errors;
            }

            if (!empty($errors = $validator->validatePassword($password))) {
                $this->errors[] = $errors;
            }

            if ($password != $passwordRepeat) {
                $this->errors[] = PASSWORDS_IN_FORM_NOT_MATCHES;
            }

            if (!empty($errors = $validator->validateName($name))) {
                $this->errors[] = $errors;
            }

            if (!empty($this->errors)) {
                echo $this->getView()->render($this->pathToPatterns, ['errCodes' => $this->errors], 'registerErr.twig',0,0,0);


            } else {
                $user = new \App\Model\User('', $password, $name, $email, '');
                $user->save();
                $this->redirect('/html/user/authorization');
            }
        } else {
            echo $this->getView()->render($this->pathToPatterns, [], 'register.twig',0,0,0);

        }
    }


    /**
     * @return mixed[]
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
}
