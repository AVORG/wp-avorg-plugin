<?php

use Avorg\DataObject\Bible;
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

	private function loadBible($data = [])
	{
		$this->mockWordPress->setMappedReturnValues("get_query_var", [
			["version", "VERSION_"],
			["drama", "DRAMA"]
		]);

		$this->mockAvorgApi->loadBibles(array_merge([
			"dam_id" => "VERSION_DRAMA"
		], $data));
	}

	public function testSetsTitle()
	{
		$this->loadBible(["name" => "the_name"]);

		$result = $this->page->filterTitle("");

		$this->assertEquals("the_name - AudioVerse", $result);
	}

	public function testReturnsBible()
	{
		$this->loadBible();

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->bible instanceof Bible;
		});
	}
}