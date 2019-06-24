<?php

use Avorg\Page\Book\Listing;

final class TestBookListing extends Avorg\TestCase
{
	/** @var Listing $bookListing */
	protected $bookListing;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$this->bookListing = $this->factory->secure("Avorg\\Page\\Book\\Listing");
	}

	public function testGetsBooks()
	{
		$this->bookListing->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getBooks");
	}

	public function testReturnsBooks()
	{
		$this->mockAvorgApi->setReturnValue("getBooks", [[
			"title" => "A Call to Medical Evangelism"
		]]);

		$this->bookListing->addUi("");

		$this->mockTwig->assertAnyCallMatches( "render", function($call) {
			$callGlobal = $call[1]["avorg"];

			return $callGlobal->books[0] instanceof \Avorg\Book;
		});
	}
}