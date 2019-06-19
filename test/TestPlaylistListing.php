<?php

final class TestPlaylistListing extends Avorg\TestCase
{
	/** @var \Avorg\Page\Playlist\Listing $presenterListing */
	protected $presenterListing;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$this->presenterListing = $this->factory->secure("Avorg\\Page\\Playlist\\Listing");
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testExists()
	{

	}
}