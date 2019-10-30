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
        $_POST = [
            'email' => 'the_email',
            'password' => 'the_password',
            'password2' => 'the_password2'
        ];

        $this->page->addUi('');

        $this->mockAvorgApi->assertAnyCallMatches('register', function($call) {
            return empty(array_diff(['the_email', 'the_password', 'the_password2'], $call));
        });
    }
}