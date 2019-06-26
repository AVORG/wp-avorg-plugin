<?php

use Avorg\DataObjectRepository\BookRepository;

final class TestBookRepository extends Avorg\TestCase
{
	/** @var BookRepository $bookRepository */
	protected $bookRepository;

	private $apiBooks;

	public function setUp()
	{
		parent::setUp();

		$this->bookRepository = $this->factory->secure("Avorg\\DataObjectRepository\\BookRepository");

		$this->apiBooks = [(object) [
			"title" => "A Call to Medical Evangelism",
			"id" => "937"
		]];

		$this->mockAvorgApi->setReturnValue("getBooks", $this->apiBooks);
	}

	public function testUsesApi()
	{
		$books = $this->bookRepository->getBooks();

		$this->assertEquals("A Call to Medical Evangelism", $books[0]->title);
	}

	public function testIsSet()
	{
		$books = $this->bookRepository->getBooks();

		$this->assertTrue($books[0]->__isset("title"));
	}

	public function testGetUrl()
	{
		$books = $this->bookRepository->getBooks();

		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/audiobooks/books/937/a-call-to-medical-evangelism.html",
			$books[0]->getUrl()
		);
	}
}