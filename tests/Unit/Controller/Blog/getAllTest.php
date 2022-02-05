<?php

class GetAllTest extends \PHPUnit\Framework\TestCase
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
    }


    /**
     * Тестирования работы подпрограммы при неавторизованном пользователе
     */
    public function testNotAuthorized(): void
    {
        unset($_SESSION['id']);
        $this->blogController->getAllAction(0);
        ob_end_clean();

        $this->assertTrue(in_array(USER_NOT_AUTHORIZED, $this->blogController->getErrors()));
    }


    public function tearDown(): void
    {
        $this->user->delete();
    }

}