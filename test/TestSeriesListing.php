<?php

use Avorg\DataObject\Series;
use Avorg\Page\Series\Listing;

final class TestSeriesListing extends Avorg\TestCase
{
	/** @var Listing $page */
	protected $page;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->secure("Avorg\\Page\\Series\\Listing");
	}

	public function testGetsSponsors()
	{
		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getAllSeries");
	}

	public function testReturnsSeries()
	{
		$this->mockAvorgApi->loadAllSeries([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->series[0] instanceof Series;
		});
	}
}