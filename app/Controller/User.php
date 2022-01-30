<?php

namespace App\Controller;

use Core\AbstractController;
use Core\Normalizer;
use Core\Validator;
use PharIo\Manifest\ElementCollection;

class User extends AbstractController
{
    public $errors;
    public function indexAction(): void
    {
        if (!isset($_SESSION['id'])) {
            echo $this->getView()->render('User/authorization.phtml', []);
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
    public function authorizationAction(): void
    {

        Normalizer::normalizaSpecialChars($_POST);
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
            $emptyFields = Validator::validateFormEmpty($_POST);
            if (count($notEmptyFields) != count($fieldsErrors)) {
                setcookie('errors', json_encode($emptyFields));
                echo $this->
                getView()->
                render('User/authorizationErr.phtml', ['errCode' => array_diff_key($fieldsErrors, array_flip($notEmptyFields))]);
                return;
            }

            setcookie('errors', '', time() - 1);
            if (empty(\App\Model\User::getByName($name))) {
                $this->errors[] = USER_NOT_EXIST;
                echo $this->getView()->render('User/authorizationErr.phtml', ['errCode' => [USER_NOT_EXIST]]);
                return;
            }

            if (($user = \App\Model\User::getByEmail($email)) === null) {
                $this->errors[] = EMAIL_NOT_EXIST;
                setcookie('errors', '', time() - 1);
                echo $this->getView()->render('User/authorizationErr.phtml', ['errCode' => [USER_NOT_EXIST]]);
                return;
            }

            if (!\App\Model\User::checkPassword($name, $password)) {
                $this->errors[] = PASSWORDS_NOT_MATCHES;
                setcookie('errors', json_encode($emptyFields));
                echo $this->getView()->render('User/authorizationErr.phtml', ['errCode' => [PASSWORDS_NOT_MATCHES]]);
                return;
            }


            setcookie('errors', '', time() - 1);
            $_SESSION['id'] = $user->getId();
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
    public function registerAction(): void
    {
        Normalizer::normalizaSpecialChars($_POST);
        foreach ($_POST as $key => $item) {
            $_POST[$key] = Normalizer::normalizeSpaces($item);
        }

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordRepeat = $_POST['password_repeat'];
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
            $emptyFields = Validator::validateFormEmpty($_POST);
            if (count($notEmptyFields) != count($fieldsErrors)) {
                setcookie('errors', json_encode($emptyFields));
                echo $this->
                getView()->
                render('User/registerErr.phtml', ['errCodes' => array_diff_key($fieldsErrors, array_flip($notEmptyFields))]);
                return;
            }

            if (\App\Model\User::getByName($name) !== null) {
                $this->errors[] = NAME_ALREADY_EXIST_REGISTRATION;
                echo $this->getView()->render('User/registerErr.phtml', ['errCodes' => [$this->errors]]);
                return;
            }

            if (\App\Model\User::getByEmail($email) !== null) {
                $this->errors[] = EMAIL_ALREADY_EXIST_REGISTRATION;
                echo $this->getView()->render('User/registerErr.phtml', ['errCodes' => [$this->errors]]);
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
                setcookie('errors', json_encode($this->errors));
                echo $this->getView()->render('User/registerErr.phtml', ['errCodes' => [$this->errors]]);
            } else {
                setcookie('errors', '', time() - 1);
                $user = new \App\Model\User('', $password, $name, $email, '');
                $user->save();
                $this->redirect('/html/user/authorization');
            }
        } else {
            setcookie('errors', '', time() - 1);
            echo $this->getView()->render('User/register.phtml', []);
        }
    }
}
