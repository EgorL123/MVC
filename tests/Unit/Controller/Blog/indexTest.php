<?php

class BlogIndexTest extends \PHPUnit\Framework\TestCase
{
    private $blogController;

    public function setUp(): void
    {
        $this->blogController = new \App\Controller\Blog();
        $this->blogController->setView(new \Core\Twig());
    }

    /**
     * Тестирование работы подпрограммы при неавторизованном пользователе
     * @throws \Core\RedirectException
     */
    public function testNotAuthorized(): void
    {
        unset($_SESSION['id']);
        $this->blogController->indexAction(0);
        ob_end_clean();

        $this->assertTrue(in_array(USER_NOT_AUTHORIZED, $this->blogController->getErrors()));
    }

    /**
     * Тестирование работы подпрограммы при авторизованном пользователе
     */
    public function testAuthorized(): void
    {
        $_SESSION['id'] = 1;
        $this->expectException(\Core\RedirectException::class);
        $this->blogController->indexAction();
        unset($_SESSION['id']);
    }

}