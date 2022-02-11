<?php

namespace App\Controller\Blog;

use App\Model\Message;
use Core\IPost;

class BlogSend extends Blog
{
    /**
     * @var int[]|mixed
     */
    public $errors;
/**
     * Валидация и отправка сообщения
     * @throws \Core\RedirectException
     */
    public function sendAction(?IPost $post, ?string $userId, array $patternNames)
    {
        $imageValidator = $this->container->get('ImageValidator');
        $textValidator = $this->container->get('MessageTextValidator');
        $imageExtension = explode('/', $_FILES['image']['type'])[1];
        if (empty($post->getBlogMessageText()->get())) {
            $this->errors[] = EMPTY_MESSAGE_TEXT;
        }

        if (!isset($userId)) {
            $this->errors[] = USER_NOT_AUTHORIZED;
        }

        if (isset($imageExtension) && !empty($errors = $imageValidator->validate($imageExtension))) {
            $this->errors[] = INCORRECT_IMAGE_TYPE;
        }

        if (!empty($errors = $textValidator->validate($post->getBlogMessageText()->get()))) {
            $this->errors[] = MESSAGE_TEXT_INCORRECT_LENGTH_MAX;
        }

        if (!empty($this->errors)) {
            return $this->getPattern(['errCodes' => $this->errors], $patternNames['sendMessageErr']);
        }

        $message = new Message();
        if (!($lastId = $message->send($userId, $post->getBlogMessageText()->get(), $imageExtension))) {
            $this->errors[] = MESSAGE_SEND_ERROR;
            return $this->getPattern(['errCodes' => $this->errors], $patternNames['sendMessageErr']);
        }

        if (isset($_FILES['image']['type'])) {
            move_uploaded_file($_FILES['image']['tmp_name'], "img" . DIRECTORY_SEPARATOR . $lastId . "." . $imageExtension);
        }

        return $this->redirect('/public_html/blog');
    }
}
