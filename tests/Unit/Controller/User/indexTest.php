<?php

class IndexTest extends \PHPUnit\Framework\TestCase
{
    private $userController;


    public function setUp(): void
    {
        $this->userController = new \App\Controller\User();
        $this->userController->setView(new \Core\Twig());
    }


    /**
     * Проверка наличия ошикби при неавторизованном пользователе
     * @throws \Core\RedirectException
     */
    public function testNotAuthorized(): void
    {
        unset($_SESSION['id']);
        $this->userController->indexAction(0);
        $this->assertTrue(in_array(USER_NOT_AUTHORIZED, $this->userController->getErrors()));
        ob_end_clean();

    }

    /**
     * Тестирование перенаправления при авторизованном пользователе
     * @throws \Core\RedirectException
     */
    public function testAuthorized(): void
    {
        $_SESSION['id'] = 0;
        $this->expectException(\Core\RedirectException::class);
        $this->userController->indexAction();
        unset($_SESSION['id']);
    }
}