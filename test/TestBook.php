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
		$this->mockAvorgApi->loadBookRecordings([]);

		$this->setBookData([ "id" => "7" ]);

		$recordings = $this->book->getRecordings();

		$this->assertInstanceOf("Avorg\\Recording", $recordings[0]);
	}

	public function testJsonEncodable()
	{
		$this->assertContains("Avorg\\iJsonEncodable", class_implements($this->book));
	}

	public function testToJsonIncludesRecordingsKey()
	{
		$this->assertObjectHasAttribute("recordings", json_decode($this->book->toJson()));
	}

	public function testToJsonIncludesRecordings()
	{
		$this->mockAvorgApi->loadBookRecordings([
			"title" => "Chapter 0 - Foreword"
		]);

		$decodedJson = json_decode($this->book->toJson());
		$decodedBook = $decodedJson->recordings[0];

		$this->assertEquals("Chapter 0 - Foreword", $decodedBook->title);
	}
}