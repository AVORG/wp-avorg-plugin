<?php

use Avorg\Presenter;

final class TestPresenter extends Avorg\TestCase
{
	/** @var Presenter $presenter */
	private $presenter;

	private $apiPresenter;

	protected function setUp()
	{
		parent::setUp();

		$this->apiPresenter = new stdClass();
		$this->apiPresenter->description = "hello world";
		$this->apiPresenter->id = "131";
		$this->apiPresenter->givenName = "first";
		$this->apiPresenter->surname = "last";
		$this->apiPresenter->suffix = "suffix";
		$this->apiPresenter->lang = "en";

		$this->presenter = $this->factory->make("Avorg\\Presenter");
		$this->presenter->setPresenter($this->apiPresenter);
	}

	public function testGetPresentations()
	{
		$this->presenter->getPresentations();

		$this->mockAvorgApi->assertMethodCalledWith("getPresenterPresentations", 131);
	}

	public function testGetDescription()
	{
		$this->assertEquals("hello world", $this->presenter->description);
	}

	public function testIssetDescription()
	{
		$this->assertTrue($this->presenter->__isset("description"));
	}

	public function testGetName()
	{
		$this->assertEquals("first last suffix", $this->presenter->getName());
	}

	public function testGetNameWithNoGivenName()
	{
		unset($this->apiPresenter->givenName);

		$this->assertEquals("last suffix", $this->presenter->getName());
	}

	public function testGetUrl()
	{
		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/sermons/presenters/131/first-last-suffix.html",
			$this->presenter->getUrl()
		);
	}
}