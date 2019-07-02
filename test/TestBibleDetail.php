<?php

use Avorg\Page\Bible\Detail;

final class TestBibleDetail extends Avorg\TestCase
{
	/** @var Detail $page */
	private $page;

	public function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->make("Avorg\\Page\\Bible\\Detail");
	}

	public function testSetsTitle()
	{
		$this->mockWordPress->setMappedReturnValues("get_query_var", [
			["version", "VERSION_"],
			["drama", "DRAMA"]
		]);

		$this->mockAvorgApi->loadBibles([
			"dam_id" => "VERSION_DRAMA",
			"name" => "the_name"
		]);

		$result = $this->page->filterTitle("");

		$this->assertEquals("the_name - AudioVerse", $result);
	}
}