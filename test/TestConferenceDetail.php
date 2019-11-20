<?php

use Avorg\DataObject\Conference;
use Avorg\Page\Conference\Detail;

final class TestConferenceDetail extends Avorg\TestCase
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

		$this->page = $this->factory->make("Avorg\\Page\\Conference\\Detail");
	}

	public function testReturnsConference()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "202");
		$this->mockAvorgApi->loadConferences(["id" => "202"], ["id" => "0"]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			$isConference = $avorg->conference instanceof Conference;
			$matchesId = $avorg->conference->id === 202;

			return $isConference && $matchesId;
		});
	}

	public function testFilterTitle()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "202");
		$this->mockAvorgApi->loadConferences([
			"id" => "202",
			"title" => "the_title"
		]);

		$result = $this->page->filterTitle("");

		$this->assertEquals("the_title - AudioVerse", $result);
	}
}