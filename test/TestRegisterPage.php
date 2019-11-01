<?php

use Avorg\Page\Register;

final class TestRegisterPage extends Avorg\TestCase
{
    /** @var Register $page */
    private $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockWordPress->passCurrentPageCheck();

        $this->page = $this->factory->make("Avorg\\Page\\Register");
    }

    public function testExists()
    {
        $_SERVER["REQUEST_URI"] = '/english/account/register';

        $_POST = [
            'email' => 'the_email',
            'password' => 'the_password',
            'password2' => 'the_password2'
        ];

        $this->page->addUi('');

        $this->mockAvorgApi->assertAnyCallMatches('register', function($call) {
            return empty(array_diff(['the_email', 'the_password', 'the_password2', 'en'], $call));
        });
    }

    public function testSetsSuccessData()
    {
        $_SERVER["REQUEST_URI"] = '/english/account/register';

        $_POST = [
            'email' => 'the_email',
            'password' => 'the_password',
            'password2' => 'the_password2'
        ];
        
        $this->mockAvorgApi->setReturnValue('register', False);

        $this->page->addUi('');

        $this->mockTwig->assertTwigTemplateRenderedWithData('page-register.twig', [
            'success' => False
        ]);
    }
}