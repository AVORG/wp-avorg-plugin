<?php

use Avorg\Page\Presenter\Listing;

final class TestPresenterDetail extends Avorg\TestCase
{
	/** @var Listing $presenterListing */
	protected $presenterDetail;

	protected function setUp(): void
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->presenterDetail = $this->factory->secure("Avorg\\Page\\Presenter\\Detail");
	}

	public function testExists()
	{
		$this->assertInstanceOf("Avorg\\Page\\Presenter\\Detail", $this->presenterDetail);
	}

	public function testGetsPresenter()
	{
		$this->mockWordPress->setReturnValues("get_query_var",  7);

		$this->presenterDetail->addUi("");

		$this->mockAvorgApi->assertMethodCalledWith("getPresenter", 7);
	}

	public function testGetDataReturnsPresenter()
	{
		$this->mockAvorgApi->setReturnValue("getPresenter", new stdClass());
		$this->mockWordPress->setReturnValues("get_query_var",  7);

		$this->presenterDetail->addUi("");

		$this->mockTwig->assertAnyCallMatches( "render", function($call) {
			$callGlobal = $call[1]["avorg"];

			return $callGlobal->presenter instanceof Avorg\DataObject\Presenter;
		});
	}

	public function testFilterTitle()
	{
		$apiPresenter = new stdClass();
		$apiPresenter->givenName = "first";
		$apiPresenter->surname = "last";
		$apiPresenter->suffix = "suffix";

		$this->mockAvorgApi->setReturnValue("getPresenter", $apiPresenter);

		$this->assertEquals(
			"first last suffix - AudioVerse",
			$this->presenterDetail->filterTitle("previous title")
		);
	}
}