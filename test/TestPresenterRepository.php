<?php

use Avorg\DataObjectRepository\PresenterRepository;

final class TestPresenterRepository extends Avorg\TestCase
{
	/** @var PresenterRepository $presenterRepository */
	private $presenterRepository;

	protected function setUp(): void
	{
		parent::setUp();

		$this->presenterRepository = $this->factory->secure("Avorg\\DataObjectRepository\\PresenterRepository");
	}

	/**
	 * @throws Exception
	 */
	public function testGetPresenter()
	{
		$this->presenterRepository->getPresenter(5);

		$this->mockAvorgApi->assertMethodCalledWith("getPresenter", 5);
	}

	public function testReturnsPresenter()
	{
		$this->mockAvorgApi->setReturnValue("getPresenter", new stdClass());

		$result = $this->presenterRepository->getPresenter(5);

		$this->assertInstanceOf("Avorg\\DataObject\\Presenter", $result);
	}

	public function testReturnsNullIfNoPresenter()
	{
		$result = $this->presenterRepository->getPresenter(5);

		$this->assertNull($result);
	}
}