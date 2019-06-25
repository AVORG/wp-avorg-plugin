<?php

use Avorg\Page\Book\Detail;

final class TestBookDetail extends Avorg\TestCase
{
	/** @var Detail $bookDetail */
	private $bookDetail;

	public function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->bookDetail = $this->factory->make("Avorg\\Page\\Book\\Detail");
	}

	public function testGetDataIncludesBook()
	{
		$this->mockAvorgApi->setReturnValue("getBook", new stdClass());
		$this->mockWordPress->setReturnValues("get_query_var",  7);

		$this->assertTwigGlobalMatchesCallback($this->bookDetail, function($avorg) {
			return $avorg->book instanceof \Avorg\Book;
		});
	}

	public function testRequestBookUsingBookId()
	{
		$this->mockWordPress->setReturnValues("get_query_var",  7);

		$this->bookDetail->addUi("");

		$this->mockAvorgApi->assertMethodCalledWith("getBook", 7);
	}

	public function testGetsEntityIdWhenRequestingBook()
	{
		$this->bookDetail->addUi("");

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
	}

	public function testGetEntityTitle()
	{
		$this->mockAvorgApi->loadBook([
			"title" => "book_title"
		]);

		$result = $this->bookDetail->filterTitle("");

		$this->assertEquals("book_title - AudioVerse", $result);
	}
}