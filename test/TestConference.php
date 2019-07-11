<?php

use Avorg\DataObject\Conference;

final class TestConference extends Avorg\TestCase
{
	/** @var Conference $conference */
	private $conference;

	protected function setUp()
	{
		parent::setUp();

		$this->conference = $this->makeConference([
			"title" => "ACF Institute 2017: Never Alone",
			"id" => "293",
		]);
	}

	public function testGetUrl()
	{
		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/sermons/conferences/293/acf-institute-2017-never-alone.html",
			$this->conference->getUrl()
		);
	}

	public function testGetRecordings()
	{
		$this->conference->getRecordings();

		$this->mockAvorgApi->assertMethodCalledWith("getConferenceRecordings", 293);
	}

	public function testGetRecordingsReturnsRecordings()
	{
		$this->mockAvorgApi->loadConferenceRecordings([]);

		$recordings = $this->conference->getRecordings();
		$recording = reset($recordings);

		$this->assertInstanceOf("Avorg\\DataObject\\Recording", $recording);
	}
}