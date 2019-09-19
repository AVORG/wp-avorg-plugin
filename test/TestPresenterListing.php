<?php

use Avorg\Page\Presenter\Listing;
use Avorg\Presenter;

final class TestPresenterListing extends Avorg\TestCase
{
    /** @var Listing $presenterListing */
    protected $presenterListing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockWordPress->passCurrentPageCheck();

        $this->presenterListing = $this->factory->secure("Avorg\\Page\\Presenter\\Listing");
    }

    public function testHasPresentersArray()
    {
        $this->presenterListing->addUi("hello world");

        $this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-presenters.twig", function ($data) {
            return is_array($data->presenters);
        });
    }

    public function testGetsPage()
    {
        $this->presenterListing->addUi("hello world");

        $this->mockWordPress->assertMethodCalledWith("get_query_var", "page");
    }

    public function testGetDataReturnsPresenters()
    {
        $this->mockAvorgApi->setReturnValue("getPresenters", [new stdClass()]);
        $this->mockWordPress->setReturnValues("get_query_var", 7);

        $this->presenterListing->addUi("");

        $this->mockTwig->assertAnyCallMatches("render", function ($call) {
            $callGlobal = $call[1]["avorg"];

            return $callGlobal->presenters[0] instanceof \Avorg\DataObject\Presenter;
        });
    }

    public function testSearchesWithLetter()
    {
        $this->mockWordPress->setReturnValue("get_query_var", "w");

        $this->presenterListing->addUi("hello world");

        $this->mockAvorgApi->assertMethodCalledWith("getPresenters", "w");
    }

    public function testDefaultsToA()
    {
        $this->mockWordPress->setReturnValue("get_query_var", '');

        $this->presenterListing->addUi("hello world");

        $this->mockAvorgApi->assertMethodCalledWith("getPresenters", "A");
    }

    public function testCachesData()
    {
        $this->presenterListing->throw404(null);
        $this->presenterListing->addUi('content');

        $this->mockAvorgApi->assertCallCount('getPresenters', 1);
    }

    public function testUsesCurrentIndexInPaginationData()
    {
        $this->mockWordPress->setReturnValue("get_query_var", "W");

        $this->presenterListing->addUi("hello world");

        $data = $this->mockTwig->getRenderedData();

        $this->assertEquals("W", $data["pagination"]["index"]);
    }

    public function testIncludesPathsInIndices()
    {
        $this->presenterListing->addUi("hello world");

        $data = $this->mockTwig->getRenderedData();

        $this->assertEquals(
            "/english/sermons/presenters/A",
            $data["pagination"]["indices"]["A"]
        );
    }
}