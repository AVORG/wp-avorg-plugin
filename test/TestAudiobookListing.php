<?php

final class TestAudiobookListing extends Avorg\TestCase
{
	/** @var \Avorg\Page\Book\Listing $presenterListing */
	protected $presenterListing;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$this->presenterListing = $this->factory->secure("Avorg\\Page\\Book\\Listing");
	}

	/**
	 * @doesNotPerformAssertions
	 */
	public function testExists()
	{

	}
}