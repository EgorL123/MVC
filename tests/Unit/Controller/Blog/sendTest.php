<?php

class SendTest extends \PHPUnit\Framework\TestCase
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
     * Тестирование отправки сообщения
     * @dataProvider additionalProviderSend
     * @param int[]|string[] $data
     */
    public function testSend(array $data): void
    {
        $_FILES['image'] = ['type' => "image\\".$data[1]];

        $_POST['text'] = $data[0];
        $this->blogController->sendAction(0);

        ob_end_clean();

        $this->assertTrue(in_array($data[2],$this->blogController->getErrors()));

    }

    /**
     * @return array<string, array<int[]|string[]>>
     */
    public function additionalProviderSend(): array
    {
        return
        [
                'empty_text' => [['','', EMPTY_MESSAGE_TEXT]],
                'incorrect_extension' => [ ['text', 'exe', INCORRECT_IMAGE_TYPE ] ],
        ];
    }


    /**
     * Тестирование отправки корректного сообщения
     * @throws \Core\RedirectException
     */
    public function testSendSuccess(): void
    {
        $_POST['text'] = $this->faker->text;
        $this->expectException(\Core\RedirectException::class);

        $this->blogController->sendAction();
    }


    /**
     * Тестирование подпрограммы при неавторизованном пользователе
     * @throws \Core\RedirectException
     */
    public function testNotAuthorized(): void
    {
        unset($_SESSION['id']);
        $_POST['text'] = $this->faker->text;

        $this->blogController->sendAction(0);
        ob_end_clean();


        $this->assertTrue(in_array(USER_NOT_AUTHORIZED, $this->blogController->getErrors()));
    }


    /**
     * Тестирование подпрограммы при вводе слишком большого текста
     * @throws \Core\RedirectException
     */
    public function testMaxTextLengthErr(): void
    {
        $_POST['text'] = $this->faker->realText(MAX_TEXT_LENGTH);

        $this->blogController->sendAction(0);
        ob_end_clean();

        $this->assertTrue(in_array(MESSAGE_TEXT_INCORRECT_LENGTH_MAX, $this->blogController->getErrors()));
    }


    /**
     * @throws Exception
     */
    public function tearDown(): void
    {
        $_POST = [];
        $_FILES = [];
        unset($_SESSION['id']);
        \App\Model\Message::deleteAll();
        $this->user->delete();
    }


}