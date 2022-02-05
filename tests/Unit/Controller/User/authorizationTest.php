<?php

class AuthorizationTest extends \PHPUnit\Framework\TestCase
{
    private \App\Model\User $user;
    private \App\Model\User $testUser;
    private $faker;
    private $userController;

    /**
     * В базу данных сохраняется пользователь с нормализованными данными($this->>user)
     * В ходе тестирования используется testUser c ненормализованными данными
     * во избежание ошибок при повторной нормализации в контроллере
     */
    public function setUp(): void
    {
        $this->faker = \Faker\Factory::create('ru_RU');

        $data =
            [
                'password' => $this->faker->password,
                'name'=>$this->faker->firstName,
                'email'=>$this->faker->email
            ];

        $this->testUser = new \App\Model\User('', $data['password'], $data['name'], $data['email'], '');

        \Core\Normalizer::normalizeSpecialChars($data);
        foreach ($data as $key => $value)
        {
            $data[$key] = \Core\Normalizer::normalizeSpaces($value);
        }

        $this->user = new \App\Model\User('', $data['password'], $data['name'], $data['email'], '');
        $this->user->save();
        $this->user->setId(\App\Model\User::getByEmail($this->user->getEmail())->getId());
        $this->userController = new \App\Controller\User();
        $this->userController->setView(new \Core\Twig());
    }

    /**
     * Проверка корректности работы контроллера при вводе в форму уже существующего имени
     * @throws \Core\RedirectException
     */
    public function testNameNotExist(): void
    {
        $_POST =
        [
            'name' => $this->faker->name,
            'email' => $this->testUser->getEmail(),
            'password' => $this->testUser->getPassword()
        ];

        $this->userController->authorizationAction(0);
        ob_end_clean();

        $this->assertTrue(in_array(NAME_NOT_EXIST, $this->userController->getErrors()));
    }


    /**
     * Проверка корректности работы контроллера при вводе в форму уже существующего email
     * @throws \Core\RedirectException
     */
    public function testEmailNotExist(): void
    {
        $_POST =
            [
                'name' =>$this->testUser->getName(),
                'email' => $this->faker->email,
                'password' => $this->testUser->getPassword()
            ];

        $this->userController->authorizationAction(0);
        ob_end_clean();
        $this->assertTrue(in_array(EMAIL_NOT_EXIST, $this->userController->getErrors()));

    }


    /**
     * Проверка корректности работы контроллера при вводе в форму неправильного пароля
     * @throws \Core\RedirectException
     */
    public function testPasswordsNotMatches(): void
    {
        $_POST =
            [
                'name' => $this->testUser->getName(),
                'email' => $this->testUser->getEmail(),
                'password' => $this->faker->password
            ];


         $this->userController->authorizationAction(0);

         ob_end_clean();

         $this->assertTrue(in_array(PASSWORDS_NOT_MATCHES, $this->userController->getErrors()));
    }

    /**
     * Проверка успешной авторизации
     * @throws \Core\RedirectException
     */
    public function testSuccessAuth(): void
    {
        $_POST =
            [
                'name' => $this->testUser->getName(),
                'email' => $this->testUser->getEmail(),
                'password' => $this->testUser->getPassword()
            ];

        $this->expectException(\Core\RedirectException::class);
        $this->userController->authorizationAction();

    }


    /**
     * Перенаправление на страницу регистрации в случае пустой формы
     * @throws \Core\RedirectException
     */
    public function testEmptyFieldRedirect(): void
    {
        $_POST = [];
        $this->expectException(\Core\RedirectException::class);
        $this->userController->authorizationAction();
    }


    /**
     * @throws Exception
     */
    public function tearDown(): void
    {
        $this->user->delete();
    }


}