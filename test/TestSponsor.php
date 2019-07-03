<?php

use Avorg\DataObject\Sponsor;

final class TestSponsor extends Avorg\TestCase
{
	/** @var Sponsor $sponsor */
	private $sponsor;

	protected function setUp()
	{
		parent::setUp();

		$this->sponsor = $this->makeSponsor([
			"title" => "A Loud and Clear Call Ministries",
			"id" => "49",
		]);
	}

	public function testGetUrl()
	{
		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/sponsors/49/a-loud-and-clear-call-ministries.html",
			$this->sponsor->getUrl()
		);
	}

	public function testGetRecordings()
	{
		$this->sponsor->getRecordings();

		$this->mockAvorgApi->assertMethodCalledWith("getSponsorRecordings", 49);
	}

	public function testGetRecordingsReturnsRecordings()
	{
		$this->mockAvorgApi->loadSponsorRecordings([]);

		$recordings = $this->sponsor->getRecordings();
		$recording = reset($recordings);

		$this->assertInstanceOf("Avorg\\DataObject\\Recording", $recording);
	}
}