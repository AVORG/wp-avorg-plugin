<?php

use Avorg\DataObject\Series;
use Avorg\Page\Series\Detail;

final class TestSeriesDetail extends Avorg\TestCase
{
	/** @var Detail $page */
	private $page;

	/**
	 * @throws ReflectionException
	 */
	public function setUp(): void
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->make("Avorg\\Page\\Series\\Detail");
	}

	public function testGetsSeries()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "7");

		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalledWith("getOneSeries", 7);
	}

	public function testReturnsSeries()
	{
		$this->mockAvorgApi->loadOneSeries([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->series instanceof Series;
		});
	}

	public function testFilterTitle()
	{
		$this->mockAvorgApi->loadOneSeries([
			"title" => "the_title"
		]);

		$result = $this->page->filterTitle("");

		$this->assertEquals("the_title - AudioVerse", $result);
	}
}