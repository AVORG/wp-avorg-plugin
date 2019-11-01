<?php

use Avorg\Page\Login;

final class TestLoginPage extends Avorg\TestCase
{
    /** @var Login $page */
    private $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockWordPress->passCurrentPageCheck();

        $this->page = $this->factory->make("Avorg\\Page\\Login");
    }

    public function testExists()
    {
        $_POST = [
            "email" => "email",
            "password" => "pass"
        ];

        $this->page->addUi('');

        $this->mockAvorgApi->assertMethodCalledWith("logIn", "email", "pass");
    }

    public function testSavesUserToSessionData()
    {
        $_POST = [
            "email" => "email",
            "password" => "pass"
        ];

        $this->mockAvorgApi->setReturnValue('logIn', ['user_data']);

        $this->page->addUi('');

        $this->assertEquals(['user_data'], $_SESSION);
    }

    public function testSetsSuccessState()
    {
        $_POST = [
            "email" => "email",
            "password" => "pass"
        ];

        $this->mockAvorgApi->setReturnValue('logIn', False);

        $this->page->addUi('');

        $this->mockTwig->assertTwigTemplateRenderedWithData('page-login.twig', [
            'success' => False
        ]);
    }

    public function testSetsSuccessStateTrue()
    {
        $_POST = [
            "email" => "email",
            "password" => "pass"
        ];

        $this->mockAvorgApi->setReturnValue('logIn', ['user_data']);

        $this->page->addUi('');

        $this->mockTwig->assertTwigTemplateRenderedWithData('page-login.twig', [
            'success' => True
        ]);
    }
}