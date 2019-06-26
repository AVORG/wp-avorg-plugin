<?php

use Avorg\Page\Book\Listing;

final class TestBookListing extends Avorg\TestCase
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

		$this->page = $this->factory->secure("Avorg\\Page\\Book\\Listing");
	}

	public function testGetsBooks()
	{
		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getBooks");
	}

	public function testReturnsBooks()
	{
		$this->mockAvorgApi->setReturnValue("getBooks", [[
			"title" => "A Call to Medical Evangelism"
		]]);

		$this->page->addUi("");

		$this->mockTwig->assertAnyCallMatches( "render", function($call) {
			$callGlobal = $call[1]["avorg"];

			return $callGlobal->books[0] instanceof \Avorg\Book;
		});
	}
}