<?php

use Avorg\Page\Bible\Listing;

final class TestBibleListing extends Avorg\TestCase
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

		$this->page = $this->factory->secure("Avorg\\Page\\Bible\\Listing");
	}

	public function testGetsBibles()
	{
		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getBibles");
	}

	public function testReturnsBibles()
	{
		$this->mockAvorgApi->loadBibles([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return reset($avorg->bibles) instanceof \Avorg\DataObject\Bible;
		});
	}
}