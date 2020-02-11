<?php

use Avorg\DataObjectRepository\PresenterRepository;
use natlib\Factory;

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

    public function testUpdatesWeights()
    {
        $this->mockAvorgApi->loadPresenter([
            "id" => 5,
            "givenName" => "first",
            "surname" => "last",
            "suffix" => "suffix",
        ]);

        $this->presenterRepository->getPresenter(5);

        $this->mockDatabase->assertMethodCalledWith(
            'incrementOrCreateWeight',
            5,
            'first last suffix',
            'Presenter',
            'http://localhost:8080/english/sermons/presenters/5/first-last-suffix.html'
        );
    }
}