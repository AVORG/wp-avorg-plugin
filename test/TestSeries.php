<?php

use Avorg\DataObject\Series;

final class TestSeries extends Avorg\TestCase
{
	/** @var Series $series */
	private $series;

	protected function setUp(): void
	{
		parent::setUp();

		$this->series = $this->makeSeries([
			"title" => "ECYC 2011 Plenary Meetings",
			"id" => "431",
		]);
	}

	public function testGetUrl()
	{
		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/sermons/series/431/ecyc-2011-plenary-meetings.html",
			$this->series->getUrl()
		);
	}

	public function testGetRecordings()
	{
		$this->series->getRecordings();

		$this->mockAvorgApi->assertMethodCalledWith("getSeriesRecordings", 431);
	}

	public function testGetRecordingsReturnsRecordings()
	{
		$this->mockAvorgApi->loadSeriesRecordings([]);

		$recordings = $this->series->getRecordings();
		$recording = reset($recordings);

		$this->assertInstanceOf("Avorg\\DataObject\\Recording", $recording);
	}
}