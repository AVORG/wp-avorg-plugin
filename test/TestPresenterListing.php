<?php

final class TestPresenterListing extends Avorg\TestCase
{
	/** @var \Avorg\Page\Presenter\Listing $presenterListing */
	protected $presenterListing;

	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$this->presenterListing = $this->factory->secure("Page\\Presenter\\Listing");
	}

	public function testHasPresentersArray()
	{
		$this->presenterListing->addUi("hello world");

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-presenters.twig", function($data) {
			return is_array($data->presenters);
		});
	}

	public function testReturnsApiPresenters()
	{
		$this->mockAvorgApi->setReturnValue("getPresenters", ["PRESENTERS"]);

		$this->presenterListing->addUi("hello world");

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-presenters.twig", function($data) {
			return $data->presenters === ["PRESENTERS"];
		});
	}

	public function testGetsLetter()
	{
		$this->presenterListing->addUi("hello world");

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "letter");
	}
}