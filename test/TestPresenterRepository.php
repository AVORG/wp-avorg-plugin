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

    public function testGetPresenterPerformance()
    {
        $dataArrays = array_fill(0, 1500, [
            'givenName' => 'Alexis',
            'surname' => 'Abrahantes Carralero',
            'hiragana' => null,
            'suffix' => '',
            'photo' => 'default.png',
            'summary' => '',
            'description' => 'Alexis Abrahantes Carralero was born in Havana, Cuba, during the height of the Cold War, to a father who was a communist diplomat and member of the military class. He witnessed the religious struggle of underground Christians who still worshipped in spite of severe oppression and persecution. He soon joined those believers. In the midst of Alexis\' conversion he suffered a severe accident, which led to a series of miraculous events that eventually brought him and his mother to the United States. ',
            'website' => '',
            'relatedURI' => null,
            'recordingCount' => '7',
            'id' => '1225',
            'photo36' => 'https://s.audioverse.org/english/gallery/persons/_/36/36/default.png',
            'photo86' => 'https://s.audioverse.org/english/gallery/persons/_/86/86/default.png',
            'lang' => 'en',
            'recordingsURI' => 'https://api2.audioverse.org/recordings/presenter/1225',
            'photo256' => 'https://s.audioverse.org/english/gallery/persons/_/256/256/default.png',
        ]);
        $this->mockAvorgApi->loadPresenters(...$dataArrays);

        $factory = new Factory();
        $factory->injectObjects(
            $this->mockAvorgApi,
            $this->mockWordPress
        );
        $repository = $factory->secure("Avorg\\DataObjectRepository\\PresenterRepository");

        $start = microtime(true);

        $presenters = $repository->getPresenters();

        foreach ($presenters as $presenter) {
            $presenter->photo256;
            $presenter->getNameReversed();
            $presenter->getUrl();
        }

        $end = microtime(true);

        $this->assertLessThan(30, $end - $start);
    }
}