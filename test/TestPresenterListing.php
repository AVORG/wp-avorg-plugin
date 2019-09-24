<?php

use Avorg\Page\Presenter\Listing;
use Avorg\Presenter;

final class TestPresenterListing extends Avorg\TestCase
{
    /** @var Listing $page */
    protected $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockWordPress->passCurrentPageCheck();

        $this->page = $this->factory->secure("Avorg\\Page\\Presenter\\Listing");
    }

    public function testHasPresentersArray()
    {
        $this->page->addUi("hello world");

        $this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-presenters.twig", function ($data) {
            return is_array($data->presenters);
        });
    }

    public function testGetsPage()
    {
        $this->page->addUi("hello world");

        $this->mockWordPress->assertMethodCalledWith("get_query_var", "page");
    }

    public function testGetDataReturnsPresenters()
    {
        $this->mockAvorgApi->setReturnValue("getPresenters", [new stdClass()]);
        $this->mockWordPress->setReturnValues("get_query_var", 7);

        $this->page->addUi("");

        $this->mockTwig->assertAnyCallMatches("render", function ($call) {
            $callGlobal = $call[1]["avorg"];

            return $callGlobal->presenters[0] instanceof \Avorg\DataObject\Presenter;
        });
    }

    public function testSearchesWithLetter()
    {
        $this->mockWordPress->setReturnValue("get_query_var", "w");

        $this->page->addUi("hello world");

        $this->mockAvorgApi->assertMethodCalledWith("getPresenters", "w");
    }

    public function testDefaultsToA()
    {
        $this->mockWordPress->setReturnValue("get_query_var", '');

        $this->page->addUi("hello world");

        $this->mockAvorgApi->assertMethodCalledWith("getPresenters", "A");
    }

    public function testCachesData()
    {
        $this->page->throw404(null);
        $this->page->addUi('content');

        $this->mockAvorgApi->assertCallCount('getPresenters', 1);
    }
}