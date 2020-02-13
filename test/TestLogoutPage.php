<?php

use Avorg\Page\Login;

final class TestLogoutPage extends Avorg\TestCase
{
    /** @var Login $page */
    private $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockWordPress->passCurrentPageCheck();

        $this->page = $this->factory->make("Avorg\\Page\\Logout");
    }

    public function testExists()
    {
        $this->page->addUi('');

        $this->mockPhp->assertMethodCalled('unsetSession');
    }
}