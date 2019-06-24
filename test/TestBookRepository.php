<?php

use Avorg\BookRepository;

final class TestBookRepository extends Avorg\TestCase
{
	/** @var BookRepository $bookRepository */
	protected $bookRepository;

	public function setUp()
	{
		parent::setUp();

		$this->bookRepository = $this->factory->secure("Avorg\\BookRepository");
	}

	public function testUsesApi()
	{
		$this->mockAvorgApi->setReturnValue("getBooks", [(object) [
			"title" => "A Call to Medical Evangelism"
		]]);

		$books = $this->bookRepository->getBooks();

		$this->assertEquals("A Call to Medical Evangelism", $books[0]->title);
	}

	public function testIsSet()
	{
		$this->mockAvorgApi->setReturnValue("getBooks", [(object) [
			"title" => "A Call to Medical Evangelism"
		]]);

		$books = $this->bookRepository->getBooks();

		$this->assertTrue($books[0]->__isset("title"));
	}
}