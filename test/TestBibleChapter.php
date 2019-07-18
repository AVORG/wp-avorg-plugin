<?php

final class TestBibleChapter extends Avorg\TestCase
{
	/**
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function testToArray()
	{
		$bibleChapter = $this->makeBibleChapter([
			"book_id" => "Gen",
            "chapter_id" => "1",
            "path" => "ENGKJVO1DA/A01___01_Genesis_____ENGKJVO1DA.mp3"
		]);

		$array = $bibleChapter->toArray();

		$this->assertEquals(
			'https://www.audioverse.org/english/download/audiobible/ENGKJVO1DAGen_1.mp3/ENGKJVO1DA%2FA01___01_Genesis_____ENGKJVO1DA.mp3',
			$array['audioFiles'][0]['streamUrl']
		);
	}

	public function testGetId()
	{
		$bibleChapter = $this->makeBibleChapter([
			"book_id" => "Gen",
			"chapter_id" => "1",
			"path" => "ENGKJVO1DA/A01___01_Genesis_____ENGKJVO1DA.mp3"
		]);

		$id = $bibleChapter->getId();

		$this->assertEquals(sha1("ENGKJVO1DA/A01___01_Genesis_____ENGKJVO1DA.mp3"), $id);
	}

	public function testToArrayIncludesEmptyPresentersArray()
	{
		$bibleChapter = $this->makeBibleChapter();

		$array = $bibleChapter->toArray();

		$this->assertEquals([], $array['presenters']);
	}

	public function testToArrayIncludesTitle()
	{
		$bibleChapter = $this->makeBibleChapter([
			"book_id" => "Gen",
			"chapter_id" => "1",
			"path" => "ENGKJVO1DA/A01___01_Genesis_____ENGKJVO1DA.mp3"
		]);

		$array = $bibleChapter->toArray();

		$this->assertEquals('Chapter 1', $array['title']);
	}
}