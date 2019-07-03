<?php

use Avorg\DataObject\Sponsor;
use Avorg\Page\Sponsor\Detail;

final class TestSponsorDetail extends Avorg\TestCase
{
	/** @var Detail $page */
	private $page;

	/**
	 * @throws ReflectionException
	 */
	public function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->make("Avorg\\Page\\Sponsor\\Detail");
	}

	public function testReturnsSponsor()
	{
		$this->mockAvorgApi->loadSponsor([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->sponsor instanceof Sponsor;
		});
	}

	public function testFilterTitle()
	{
		$this->mockAvorgApi->loadSponsor([
			"title" => "the_title"
		]);

		$result = $this->page->filterTitle("");

		$this->assertEquals("the_title - AudioVerse", $result);
	}
}