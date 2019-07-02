<?php

use Avorg\DataObjectRepository\RecordingRepository;

final class TestRecordingRepository extends Avorg\TestCase
{
    /** @var RecordingRepository $plugin */
    protected $recordingRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->recordingRepository = $this->factory->secure("Avorg\\DataObjectRepository\\RecordingRepository");
    }

    /**
     * @throws Exception
     */
    public function testReturnsRecordings()
    {
        $entry = new stdClass();
        $entry->recordings = "item";
        $this->mockAvorgApi->setReturnValue("getRecordings", [$entry]);

        $result = $this->recordingRepository->getRecordings();

        $this->assertInstanceOf("\\Avorg\\DataObject\\Recording", $result[0]);
    }

    public function testUsesUnwrappedRecordingWhenInstantiatingRecording()
    {
        $entry = [
			"presenters" => [
				[
					"photo256" => "photo_url"
				]
			]
        ];

        $entryObject = json_decode(json_encode($entry), FALSE);

        $this->mockAvorgApi->setReturnValue("getRecordings", [$entryObject]);

        $result = $this->recordingRepository->getRecordings();

        $this->assertEquals("photo_url", $result[0]->getPresenters()[0]["photo"]);
    }

    public function testLoadsRecordingsWithRecordingUrl()
    {
        $apiRecording = $this->convertArrayToObjectRecursively([
			"lang" => "en",
			"id" => "1836",
			"title" => 'E.P. Daniels and True Revival'
        ]);

        $this->mockAvorgApi->setReturnValue("getRecordings", [$apiRecording]);

        $result = $this->recordingRepository->getRecordings();

        $this->assertEquals(
            "http://localhost:8080/english/sermons/recordings/1836/ep-daniels-and-true-revival.html",
            $result[0]->getUrl()
        );
    }
}