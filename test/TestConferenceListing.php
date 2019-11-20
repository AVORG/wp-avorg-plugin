<?php

use Avorg\DataObject\Conference;
use Avorg\Page\Conference\Listing;

final class TestConferenceListing extends Avorg\TestCase
{
	/** @var Listing $page */
	protected $page;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp(): void
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->secure("Avorg\\Page\\Conference\\Listing");
	}

	public function testGetsConferences()
	{
		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getConferences");
	}

	public function testReturnsBooks()
	{
		$this->mockAvorgApi->loadConferences([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->conferences[0] instanceof Conference;
		});
	}
}