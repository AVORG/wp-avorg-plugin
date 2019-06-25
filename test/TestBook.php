<?php

final class TestBook extends Avorg\TestCase
{
	/** @var \Avorg\Book $book */
	private $book;

	public function setUp()
	{
		parent::setUp();

		$this->book = $this->factory->make("Avorg\\Book");
	}

	private function setBookData($data)
	{
		$this->book->setData((object) $data);
	}

	public function testGetTitle()
	{
		$this->setBookData([
			"title" => "A Call to Medical Evangelism"
		]);

		$this->assertEquals("A Call to Medical Evangelism", $this->book->title);
	}

	public function testGetRecordings()
	{
		$this->setBookData([ "id" => "7" ]);

		$this->book->getRecordings();

		$this->mockAvorgApi->assertMethodCalledWith("getBookRecordings", 7);
	}

	public function testGetRecordingsReturnsRecordings()
	{
		$apiRecording = $this->convertArrayToObjectRecursively([
			"recordings" => [
				"lang" => "en",
				"id" => "1836",
				"title" => 'E.P. Daniels and True Revival'
			]
		]);

		$this->mockAvorgApi->setReturnValue("getBookRecordings", [$apiRecording]);

		$this->setBookData([ "id" => "7" ]);

		$recordings = $this->book->getRecordings();

		$this->assertInstanceOf("Avorg\\Recording", $recordings[0]);
	}
}