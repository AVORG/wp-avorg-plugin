<?php

use Avorg\Presenter;

final class TestPresenter extends Avorg\TestCase
{
	/** @var Presenter $presenter */
	private $presenter;

	protected function setUp()
	{
		parent::setUp();

		$apiPresenter = new stdClass();
		$apiPresenter->description = "hello world";
		$apiPresenter->id = "131";
		$apiPresenter->givenName = "first";
		$apiPresenter->surname = "last";
		$apiPresenter->suffix = "suffix";

		$presentationRepository = $this->factory->secure("Avorg\\PresentationRepository");
		$this->presenter = new Avorg\Presenter($apiPresenter, $presentationRepository);
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
}