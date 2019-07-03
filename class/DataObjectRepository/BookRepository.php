<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObjectRepository;
use Exception;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class BookRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Book";

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getBooks()
	{
		$rawBooks = $this->api->getBooks();

		return $this->makeDataObjects($rawBooks);
	}

	/**
	 * @param $id
	 * @return mixed
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function getBook($id)
	{
		$rawBook = $this->api->getBook($id);

		return $this->makeDataObject($rawBook);
	}
}