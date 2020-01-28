<?php

use Avorg\Page\Search;

final class TestSearchPage extends Avorg\TestCase
{
    /** @var Search $page */
    private $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockWordPress->passCurrentPageCheck();

        $this->page = $this->factory->make("Avorg\\Page\\Search");
    }

    public function testExists()
    {
        $this->page->addUi('');

        $this->mockWordPress->assertMethodCalledWith('get_query_var', 'q');
    }
}