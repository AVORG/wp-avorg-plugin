<?php

use Avorg\DataObject\Sponsor;
use Avorg\Page\Sponsor\Listing;

final class TestSponsorListing extends Avorg\TestCase
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

		$this->page = $this->factory->secure("Avorg\\Page\\Sponsor\\Listing");
	}

	public function testGetsSponsors()
	{
		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getSponsors");
	}

	public function testReturnsSponsors()
	{
		$this->mockAvorgApi->loadSponsors([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($avorg) {
			return $avorg->sponsors[0] instanceof Sponsor;
		});
	}
}