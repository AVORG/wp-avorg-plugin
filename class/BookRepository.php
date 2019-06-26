<?php

namespace Avorg;


use Exception;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class BookRepository
{
	/** @var AvorgApi $api */
	private $api;

	/** @var Factory $factory */
	private $factory;

	public function __construct(AvorgApi $api, Factory $factory)
	{
		$this->api = $api;
		$this->factory = $factory;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getBooks()
	{
		return array_map([$this, 'makeBook'], $this->api->getBooks() ?: []);
	}

	/**
	 * @param $id
	 * @return mixed
	 * @throws ReflectionException
	 */
	public function getBook($id)
	{
		$response = $this->api->getBook($id);

		return $this->makeBook($response);
	}

	/**
	 * @param $rawBook
	 * @return mixed
	 * @throws ReflectionException
	 */
	private function makeBook($rawBook)
	{
		return $this->factory->make("Avorg\\DataObject\\Book")->setData($rawBook);
	}
}