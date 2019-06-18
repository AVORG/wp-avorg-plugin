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

		$presentationRepository = $this->factory->secure("Avorg\\PresentationRepository");
		$this->presenter = new Avorg\Presenter($this->apiPresenter, $presentationRepository);
	}

	public function testGetPresentations()
	{
		$this->presenter->getPresentations();

		$this->mockAvorgApi->assertMethodCalledWith("getPresenterPresentations", 131);
	}

	public function testGetDescription()
	{
		$result = $this->presenter->description;

		$this->assertEquals("hello world", $result);
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
}