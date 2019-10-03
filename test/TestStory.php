<?php

use Avorg\DataObject\Story;

final class TestStory extends Avorg\TestCase
{
	/** @var Story $story */
	private $story;

	protected function setUp()
	{
		parent::setUp();

		$this->story = $this->makeStory([
			"id" => "1167",
			"title" => "Acts of the Apostles",
		]);
	}

	public function testGetUrl()
	{
		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/audiobooks/stories/1167/acts-of-the-apostles.html",
			$this->story->getUrl()
		);
	}

	public function testGetRecordings()
	{
		$this->story->getRecordings();

		$this->mockAvorgApi->assertMethodCalledWith("getBookRecordings", "1167");
	}

	public function testGetRecordingsReturnsRecordings()
	{
		$this->mockAvorgApi->loadBookRecordings([]);

		$recordings = $this->story->getRecordings();
		$recording = reset($recordings);

		$this->assertInstanceOf("Avorg\\DataObject\\Recording", $recording);
	}

	public function testIncludesRecordingsInArray()
	{
		$this->mockAvorgApi->loadBookRecordings([
			"title" => "the_recording"
		]);

		$array = $this->story->toArray();
		$recording = reset($array["recordings"]);

		$this->assertEquals("the_recording", $recording["title"]);
	}
}