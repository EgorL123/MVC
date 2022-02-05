<?php

class DeleteTest extends \PHPUnit\Framework\TestCase
{
    private $user;
    private $blogController;
    private $faker;

    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create('ru_RU');
        $data =
            [
                'password' => $this->faker->password,
                'name'=>$this->faker->firstName,
                'email'=>$this->faker->email
            ];
        \Core\Normalizer::normalizeSpecialChars($data);

        foreach ($data as $key => $value)
        {
            $data[$key] = \Core\Normalizer::normalizeSpaces($value);
        }

        $this->user = new \App\Model\User('', $data['password'], $data['name'], $data['email'], '');
        $this->user->save();
        $this->user->setId(\App\Model\User::getByEmail($this->user->getEmail())->getId());
        $this->blogController = new \App\Controller\Blog();
        $this->blogController->setView(new \Core\Twig());
        $_SESSION['id'] = $this->user->getId();
    }


    /**
     * Тестирование удаления сообщения пользователем, не являющимся администратором
     */
    public function testDelete(): void
    {
        $this->blogController->deleteAction();
        $this->assertTrue(in_array(NOT_ADMIN_ACCESS, $this->blogController->getErrors()));
    }


    public function testDeleteSuccess(): void
    {

        $id = \App\Model\Message::send($this->user->getId(),$this->faker->text,'');
        $_POST['id'] = $id;
        $_SESSION['id'] = 0; // идентификатор админа в config.php

        $this->expectException(\Core\RedirectException::class);
        $this->blogController->deleteAction();
    }

    public function tearDown(): void
    {
        unset($_SESSION['id']);
        $_POST = [];
        \App\Model\Message::deleteAll();
        $this->user->delete();
    }


}