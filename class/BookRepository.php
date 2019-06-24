<?php

namespace Avorg;


use Exception;
use natlib\Factory;

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
		return array_map(function($rawBook) {
			return $this->factory->make("Avorg\\Book")->setData($rawBook);
		}, $this->api->getBooks() ?: []);
	}
}