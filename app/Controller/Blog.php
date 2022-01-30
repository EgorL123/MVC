<?php

namespace App\Controller;

use App\Model\Message;
use Core\AbstractController;
use Core\DataBase;
use Core\Normalizer;
use Core\Validator;

class Blog extends AbstractController
{
    public array $errors = [];

     /**
     * В случае, если пользователь не авторизован, перенаправит на страницу авторизации,
     * в противном случае запускается контроллер вывода соообщений
     * @throws \Core\RedirectException
     */
    public function indexAction(): void
    {

        if (!isset($_SESSION['id'])) {
            $this->errors[] = USER_NOT_AUTHORIZED;
            if ($this->getView() !== null) {
                echo $this->getView()->render('Blog/blogErr.phtml', ['errCodes' => $this->errors]);
            }

            die;
        }

        $this->redirect('/html/blog/getAll');
    }


    /**
     * Валидация и отправка сообщения
     * @throws \Core\RedirectException
     */
    public function sendAction(): void
    {
        $imageExtension = explode('/', $_FILES['image']['type'])[1];
        Normalizer::normalizaSpecialChars($_POST);
        if (empty($_POST['text'])) {
            $this->errors[] = EMPTY_MESSAGE_TEXT;
        }

        if (isset($imageExtension) && !empty($errors = Validator::validateImageType($imageExtension))) {
            $this->errors = $errors;
        }

        if (!empty($errors = Validator::validateMessageText($_POST['text']))) {
            $this->errors[] = $errors;
        }

        if (!empty($this->errors)) {
            echo $this->getView()->render('Blog/sendMessageErr.phtml', ['errCodes' => [$this->errors]]);
            die;
        }

        Message::send($_SESSION['id'], $_POST['text'], $imageExtension);
        move_uploaded_file($_FILES['image']['tmp_name'], "img" . DIRECTORY_SEPARATOR . Message::getLastId() . "." . $imageExtension);
        $this->redirect('/html/blog');
    }


    /**
     * Вывод всех сообщений на страницу блога
     */
    public function getAllAction(): void
    {

        if (!isset($_SESSION['id'])) {
            $this->errors[] = USER_NOT_AUTHORIZED;
            echo $this->getView()->render('Blog/blogErr.phtml', ['errCodes' => $this->errors]);
            die;
        }

        $messages = Message::getAll();
        echo $this->getView()->render('Blog/Blog.phtml', ['errCodes' => [$this->errors], 'messages' => $messages]);
    }


    /**
     * Удаление сообщения (для администратора)
     * @throws \Core\RedirectException
     */
    public function deleteAction(): void
    {
        Message::delete($_POST['id']);
        $this->redirect('/html/blog');
    }
}
