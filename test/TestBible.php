<?php

final class TestBible extends Avorg\TestCase
{

	/**
	 * @throws ReflectionException
	 */
	public function testGetUrl()
	{
		$bible = $this->makeBible([
            "dam_id" => "ENGESV",
            "drama" => 2
		]);

		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/audiobibles/books/ENGESV/2",
			$bible->getUrl()
		);
	}

	/**
	 * @throws ReflectionException
	 */
	public function testGetBooks()
	{
		$this->mockAvorgApi->setReturnValue("getBibleBooks", [[]]);

		$bible = $this->makeBible();
		$books = $bible->getBooks();

		$this->assertInstanceOf("Avorg\\DataObject\\BibleBook", $books[0]);
	}

	public function testUsesIdToGetBibleBooks()
	{
		$bible = $this->makeBible([
			"dam_id" => "ENGESV",
			"drama" => 2
		]);

		$bible->getBooks();

		$this->mockAvorgApi->assertMethodCalledWith("getBibleBooks", "ENGESV2");
	}
}