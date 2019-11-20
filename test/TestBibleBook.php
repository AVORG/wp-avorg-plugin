<?php

final class TestBibleBook extends Avorg\TestCase
{
	public function setUp(): void
	{
		parent::setUp();

		$this->mockWordPress->setMappedReturnValues("get_query_var", [
			["bible_id", "BIBLE_ID"],
			["book_id", "BOOK_ID"],
			["testament_id", "TESTAMENT_ID"]
		]);
	}

	/**
	 * @throws ReflectionException
	 */
	public function testGetChapters()
	{
		$bibleBook = $this->makeBibleBook([
			"dam_id" => "BIBLE_ID",
			"book_id" => "BOOK_ID",
			"testament" => "TESTAMENT_ID"
		]);

		$bibleBook->getChapters();

		$this->mockAvorgApi->assertMethodCalledWith("getBibleChapters",
			"BIBLE_ID", "BOOK_ID", "TESTAMENT_ID");
	}

	/**
	 * @throws ReflectionException
	 */
	public function testGetChaptersReturnsChapters()
	{
		$this->mockAvorgApi->loadBibleChapters([]);

		$bibleBook = $this->makeBibleBook();

		$result = $bibleBook->getChapters();

		$this->assertInstanceOf( "Avorg\\DataObject\\Recording\\BibleChapter", $result[0]);
	}

	public function testIncludesChapterInToArray()
	{
		$this->mockAvorgApi->loadBibleChapters([
			"book_id" => "Gen",
            "chapter_id" => "1",
            "path" => "ENGKJVO1DA/A01___01_Genesis_____ENGKJVO1DA.mp3"
		]);

		$bibleBook = $this->makeBibleBook();

		$array = $bibleBook->toArray();

		$this->assertArrayHasKey("chapters", $array);

		$this->assertEquals($array['chapters'][0]['audioFiles'][0]['type'], 'audio/mp3');
	}
}