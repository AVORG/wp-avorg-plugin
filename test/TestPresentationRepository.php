<?php

final class TestPresentationRepository extends Avorg\TestCase
{
    /** @var \Avorg\PresentationRepository $plugin */
    protected $presentationRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->presentationRepository = $this->factory->getPresentationRepository();
    }

    /**
     * @throws Exception
     */
    public function testReturnsPresentations()
    {
        $entry = new stdClass();
        $entry->recordings = "item";
        $this->mockAvorgApi->setReturnValue("getPresentations", [$entry]);

        $result = $this->presentationRepository->getPresentations();

        $this->assertInstanceOf("\\Avorg\\Presentation", $result[0]);
    }

    public function testUsesUnwrappedRecordingWhenInstantiatingRecording()
    {
        $entry = [
            "recordings" => [
                "presenters" => [
                    [
                        "photo86" => "photo_url"
                    ]
                ]
            ]
        ];

        $entryObject = json_decode(json_encode($entry), FALSE);

        $this->mockAvorgApi->setReturnValue("getPresentations", [$entryObject]);

        $result = $this->presentationRepository->getPresentations();

        $this->assertEquals("photo_url", $result[0]->getPresenters()[0]["photo"]);
    }

    public function testLoadsPresentationsWithPresentationUrl()
    {
        $apiRecording = $this->convertArrayToObjectRecursively([
            "recordings" => [
                "lang" => "en",
                "id" => "1836",
                "title" => 'E.P. Daniels and True Revival'
            ]
        ]);

        $this->mockAvorgApi->setReturnValue("getPresentations", [$apiRecording]);

        $result = $this->presentationRepository->getPresentations();

        $this->assertEquals(
            "/english/sermons/recordings/1836/E.P.%20Daniels%20and%20True%20Revival.html",
            $result[0]->getUrl()
        );
    }
}