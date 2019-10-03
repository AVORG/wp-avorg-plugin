<?php

use Avorg\DataObject\BibleBook;
use Avorg\Page\Bible\Detail;

final class TestBibleBookDetail extends Avorg\TestCase
{
	/** @var Detail $page */
	private $page;

	public function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->make("Avorg\\Page\\BibleBook\\Detail");

		$this->mockAvorgApi->loadBibleBooks([
			"dam_id" => "ENGESV2",
			"name" => "Genesis",
			"book_id" => "Gen",
			"chapters" => "50",
			"testament" => "O",
			"drama" => 2
		]);

		$this->mockWordPress->setMappedReturnValues("get_query_var", [
			["bible_id", "VERSION_"],
			["drama", "DRAMA"],
			["book_id", "Gen"]

		]);
	}

	public function testSetsTitle()
	{
		$result = $this->page->filterTitle("");

		$this->assertEquals("Genesis - AudioVerse", $result);
	}

	public function testGetsBibleId()
	{
		$this->page->filterTitle("");

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "bible_id");
	}

	public function testPassesBibleBookToView()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "Gen");

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->book instanceof BibleBook;
		});
	}

	public function testGetsBibleBooks()
	{
		$this->page->filterTitle("");

		$this->mockAvorgApi->assertMethodCalledWith("getBibleBooks", "VERSION_DRAMA");
	}
}