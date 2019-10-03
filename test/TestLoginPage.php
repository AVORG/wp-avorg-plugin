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

    /**
     * @doesNotPerformAssertions
     */
    public function testExists()
    {
    }
}