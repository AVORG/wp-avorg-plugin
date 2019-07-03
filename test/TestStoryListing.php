<?php

use Avorg\DataObject\Story;
use Avorg\Page\Bible\Listing;

final class TestStoryListing extends Avorg\TestCase
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

		$this->page = $this->factory->secure("Avorg\\Page\\Story\\Listing");
	}

	public function testGetsStories()
	{
		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getStories");
	}

	public function testReturnsBibles()
	{
		$this->mockAvorgApi->loadStories([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return reset($avorg->stories) instanceof Story;
		});
	}
}