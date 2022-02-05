<?php

namespace App\Controller;

use App\Model\Message;
use Core\AbstractController;
use Core\DataBase;
use Core\Normalizer;
use Core\Validator;
use PharIo\Manifest\ElementCollection;
use PHPStan\BetterReflection\Identifier\Identifier;

class Blog extends AbstractController
{
    private array $errors = [];
    private const PATH_TO_IMG = '../../html/img/';
    private string $pathToPatterns = PATH_TO_ERROR_PATTERNS . DIRECTORY_SEPARATOR . "Blog";

    /**
     * @return mixed[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * В случае, если пользователь не авторизован, перенаправит на страницу авторизации,
     * в противном случае запускается контроллер вывода соообщений
     * @throws \Core\RedirectException
     */
    public function indexAction(bool $print = true)
    {

        if(!$print)
        {
            ob_start();
        }

        if (!isset($_SESSION['id'])) {
            $this->errors[] = USER_NOT_AUTHORIZED;

            echo $this->getView()->render($this->pathToPatterns,
                [
                    'errCodes' => \Core\Validator::getErrors($this->errors)
                ],
                'blogErr.twig', 0, 0, 0);

            return null;
        }

        return $this->redirect('/html/blog/getAll');

    }


    /**
     * Валидация и отправка сообщения
     * @throws \Core\RedirectException
     */
    public function sendAction(bool $print = true)
    {
        if(!$print)
        {
            ob_start();
        }

        $imageExtension = explode('/', $_FILES['image']['type'])[1];
        Normalizer::normalizeSpecialChars($_POST);

        if (empty($_POST['text'])) {
            $this->errors[] = EMPTY_MESSAGE_TEXT;
        }

        if(!isset($_SESSION['id']))
        {
            $this->errors[] = USER_NOT_AUTHORIZED;
        }

        if (isset($imageExtension) && !empty($errors = Validator::validateImageType($imageExtension))) {
            $this->errors[] = INCORRECT_IMAGE_TYPE;
        }

        if (!empty($errors = Validator::validateMessageText($_POST['text']))) {
            $this->errors[] = MESSAGE_TEXT_INCORRECT_LENGTH_MAX;
        }

        if (!empty($this->errors)) {

            echo $this->getView()->render($this->pathToPatterns,
                ['errCodes' => $this->errors], 'sendErr.twig', 0, 0, 0);

            return null;

        }


        Message::send($_SESSION['id'], $_POST['text'], $imageExtension);

        if(isset($_FILES['image']['type'])) {
            move_uploaded_file($_FILES['image']['tmp_name'], "img" . DIRECTORY_SEPARATOR . Message::getLastId() . "." . $imageExtension);
        }

        return $this->redirect('/html/blog');
    }


    /**
     * Вывод всех сообщений на страницу блога
     */
    public function getAllAction(bool $print = true): void
    {
        if(!$print)
        {
            ob_start();
        }

        if (!isset($_SESSION['id'])) {
            $this->errors[] = USER_NOT_AUTHORIZED;
            echo $this->getView()->render($this->pathToPatterns,
                ['errCodes' => $this->errors], 'blogErr.twig', 0, 0, 0);
            return;
        }


        $messages = Message::getAll();
        echo $this->getView()->render($this->pathToPatterns
            , ['messages' => $messages, 'pathToImages' => self::PATH_TO_IMG, 'userId' => $_SESSION['id'],
                'admins' => ADMINS], 'Blog.twig', 0, 0, 0);

    }


    /**
     * Удаление сообщения (для администратора)
     * @throws \Core\RedirectException
     */
    public function deleteAction(): void
    {
        if (in_array($_SESSION['id'], ADMINS)) {
            Message::delete($_POST['id']);
            $this->redirect('/html/blog');
        }

        $this->errors[] = NOT_ADMIN_ACCESS;
    }

}
