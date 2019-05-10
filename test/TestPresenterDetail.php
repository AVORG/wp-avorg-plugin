<?php

final class TestPresenterDetail extends Avorg\TestCase
{
	/** @var \Avorg\Page\Presenter\Listing $presenterListing */
	protected $presenterDetail;

	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$this->presenterDetail = $this->factory->secure("Page\\Presenter\\Detail");
	}

	public function testExists()
	{
		$this->assertInstanceOf("Avorg\\Page\\Presenter\\Detail", $this->presenterDetail);
	}
}