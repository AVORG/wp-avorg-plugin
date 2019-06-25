<?php

final class TestBook extends Avorg\TestCase
{
	public function setUp()
	{
		parent::setUp();
	}

	public function testGetTitle()
	{
		$book = $this->makeBook([
			"title" => "A Call to Medical Evangelism"
		]);

		$this->assertEquals("A Call to Medical Evangelism", $book->title);
	}

	public function testGetRecordings()
	{
		$book = $this->makeBook([ "id" => "7" ]);

		$book->getRecordings();

		$this->mockAvorgApi->assertMethodCalledWith("getBookRecordings", 7);
	}

	public function testGetRecordingsReturnsRecordings()
	{
		$this->mockAvorgApi->loadBookRecordings([]);

		$book = $this->makeBook([ "id" => "7" ]);

		$recordings = $book->getRecordings();

		$this->assertInstanceOf("Avorg\\Recording", $recordings[0]);
	}

	public function testJsonEncodable()
	{
		$book = $this->makeBook();

		$this->assertContains("Avorg\\iArrayEncodable", class_implements($book));
	}

	public function testToJsonIncludesRecordingsKey()
	{
		$book = $this->makeBook();

		$this->assertObjectHasAttribute("recordings", json_decode($book->toJson()));
	}

	public function testToJsonIncludesRecordings()
	{
		$book = $this->makeBook();

		$this->mockAvorgApi->loadBookRecordings([
			"title" => "Chapter 0 - Foreword"
		]);

		$decodedJson = json_decode($book->toJson());
		$decodedBook = $decodedJson->recordings[0];

		$this->assertEquals("Chapter 0 - Foreword", $decodedBook->title);
	}
}