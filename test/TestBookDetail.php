<?php

use Avorg\Page\Book\Detail;

final class TestBookDetail extends Avorg\TestCase
{
	/** @var Detail $page */
	private $page;

	public function setUp(): void
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->make("Avorg\\Page\\Book\\Detail");
	}

	public function testGetDataIncludesBook()
	{
		$this->mockAvorgApi->loadBook([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->book instanceof \Avorg\DataObject\Book;
		});
	}

	public function testRequestBookUsingBookId()
	{
		$this->mockWordPress->setReturnValues("get_query_var",  7);

		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalledWith("getBook", 7);
	}

	public function testGetsEntityIdWhenRequestingBook()
	{
		$this->page->addUi("");

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
	}

	public function testGetEntityTitle()
	{
		$this->mockAvorgApi->loadBook([
			"title" => "book_title"
		]);

		$result = $this->page->filterTitle("");

		$this->assertEquals("book_title - AudioVerse", $result);
	}
}