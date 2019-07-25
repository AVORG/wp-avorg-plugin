<?php

use Avorg\Endpoint\Recording;

final class TestRelatedSermonsEndpoint extends Avorg\TestCase
{
	/** @var Recording $endpoint */
	protected $endpoint;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\RelatedSermons");
	}

	public function testReturnsJson()
	{
		$output = $this->endpoint->getOutput();

		$this->assertJson($output);
	}

	public function testGetsPresentation()
	{
		$this->mockWordPress->setReturnValue("get_query_var", 7);

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getRecording", 7);
	}

	public function testGetsRelatedConferencePresentations()
	{
		$this->mockAvorgApi->loadRecording([
			"conferenceId" => "100"
		]);

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith(
			"getConferenceRecordings", "100");
	}

	public function testGetsSeriesRecordings()
	{
		$this->mockAvorgApi->loadRecording([
			"seriesId" => "28"
		]);

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith(
			"getSeriesRecordings", "28");
	}

	public function testSkipsSeriesRecordings()
	{
		$this->mockAvorgApi->loadRecording([
			"seriesId" => "0"
		]);

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodNotCalled("getSeriesRecordings");
	}

	public function testGetsSponsorRecordings()
	{
		$this->mockAvorgApi->loadRecording([
			"sponsorId" => "8"
		]);

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalled("getSponsorRecordings", "8");
	}

	public function testGetsPresenterPresentations()
	{
		$this->mockAvorgApi->loadRecording([
			"presenters" => [
				["id" => "1"], ["id" => "2"]
			]
		]);

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalled("getPresenterRecordings", "1");
		$this->mockAvorgApi->assertMethodCalled("getPresenterRecordings", "2");
	}

	public function testReturnsPresentations()
	{
		$this->mockAvorgApi->loadRecording(["presenters" => [[]]]);
		$this->mockAvorgApi->loadConferenceRecordings(["id" => "1"]);
		$this->mockAvorgApi->loadSeriesRecordings(["id" => "2"]);
		$this->mockAvorgApi->loadSponsorRecordings(["id" => "3"]);
		$this->mockAvorgApi->loadPresenterRecordings(["id" => "4"]);

		$result = $this->endpoint->getOutput();
		$json_decode = json_decode($result);

		$this->assertEquals("1", $json_decode[0]->id);
	}

	public function testRemovesDupes()
	{
		$this->mockAvorgApi->loadRecording(["presenters" => [[]]]);
		$this->mockAvorgApi->loadConferenceRecordings([]);
		$this->mockAvorgApi->loadSeriesRecordings([]);
		$this->mockAvorgApi->loadSponsorRecordings([]);
		$this->mockAvorgApi->loadPresenterRecordings([]);

		$result = $this->endpoint->getOutput();

		$this->assertCount(1, json_decode($result));
	}
}