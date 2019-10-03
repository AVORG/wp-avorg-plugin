<?php

use Avorg\DataObjectRepository\BookRepository;

final class TestBookRepository extends Avorg\TestCase
{
	/** @var BookRepository $repository */
	protected $repository;

	private $apiBooks;

	public function setUp(): void
	{
		parent::setUp();

		$this->repository = $this->factory->secure("Avorg\\DataObjectRepository\\BookRepository");

		$this->apiBooks = [(object) [
			"title" => "A Call to Medical Evangelism",
			"id" => "937"
		]];

		$this->mockAvorgApi->setReturnValue("getBooks", $this->apiBooks);
	}

	public function testUsesApi()
	{
		$books = $this->repository->getDataObjects();

		$this->assertEquals("A Call to Medical Evangelism", $books[0]->title);
	}

	public function testIsSet()
	{
		$books = $this->repository->getDataObjects();

		$this->assertTrue($books[0]->__isset("title"));
	}

	public function testGetUrl()
	{
		$books = $this->repository->getDataObjects();

		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/audiobooks/books/937/a-call-to-medical-evangelism.html",
			$books[0]->getUrl()
		);
	}
}