<?php

class RegistrationTest extends \PHPUnit\Framework\TestCase
{
    private \App\Model\User $user;
    private $faker;
    private $userController;

    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create('ru_RU');
        $this->user = new \App\Model\User
        ('', $this->faker->password,
            $this->faker->firstName, $this->faker->email, '');

        $this->user->save();
        $this->user->setId(\App\Model\User::getByEmail($this->user->getEmail())->getId());
        $this->userController = new \App\Controller\User();
        $this->userController->setView(new \Core\Twig());
    }


    /**
     * Проверка работы регистрации, если email уже существует
     */
    public function testEmailExist(): void
    {
        $_POST = [
            'name' => $this->faker->name,
            'email' => $this->user->getEmail(),
            'password' => $this->user->getPassword(),
            'password_repeat' => $this->user->getPassword()
        ];

        $this->userController->registerAction(0);

        ob_end_clean();

        $this->assertTrue(in_array(EMAIL_ALREADY_EXIST_REGISTRATION, $this->userController->getErrors()));
    }

    /**
     * Проверка работы регистрации, если имя уже существует
     */
    public function testNameExist(): void
    {
        $_POST =
        [
            'name' => $this->user->getName(),
            'email' => $this->faker->email,
            'password' => $this->user->getPassword(),
            'password_repeat' => $this->user->getPassword()

        ];

        $this->userController->registerAction(0);

        ob_end_clean();

        $this->assertTrue(in_array(NAME_ALREADY_EXIST_REGISTRATION, $this->userController->getErrors()));
    }


    /**
     * Проверка успешной регистрации
     * @throws \Core\RedirectException
     */
    public function testRegisterSuccess(): void
    {
        $password = $this->faker->password;
        $_POST =
            [
                'name' => $this->faker->firstName,
                'email' => $this->faker->email,
                'password' => $password,
                'password_repeat' => $password

            ];


        try
        {
            $this->userController->registerAction();
        } catch (\Core\RedirectException $e)
        {
            $user = new \App\Model\User('',$_POST['password'], $_POST['name'], $_POST['email']);
            $user->setId(\App\Model\User::getByEmail($_POST['email'])->getId());
            $this->assertTrue(empty($this->userController->getErrors()));

            $user->delete();
        }


    }

    /**
     * @throws Exception
     */
    public function tearDown(): void
    {
        $this->user->delete();
    }


}