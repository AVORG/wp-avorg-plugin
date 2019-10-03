<?php

use Avorg\DataObject\Story;
use Avorg\Page\Story\Detail;

final class TestStoryDetail extends Avorg\TestCase
{
	/** @var Detail $page */
	private $page;

	public function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->make("Avorg\\Page\\Story\\Detail");
	}

	public function testGetsStory()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "1167");

		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalledWith("getBook", "1167");
	}

	public function testReturnsStory()
	{
		$this->mockAvorgApi->loadBook([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->story instanceof Story;
		});
	}

	public function testFilterTitle()
	{
		$this->mockAvorgApi->loadBook([
			"title" => "book_title"
		]);

		$result = $this->page->filterTitle("");

		$this->assertEquals("book_title - AudioVerse", $result);
	}
}