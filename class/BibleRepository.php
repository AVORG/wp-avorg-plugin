<?php

namespace Avorg;


use natlib\Factory;

if (!defined('ABSPATH')) exit;

class BibleRepository
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

	public function getBibles()
	{
		$rawBibles = $this->api->getBibles();

		return array_map(function($rawBible) {
			return $this->factory->make("Avorg\\DataObject\\Bible")->setData($rawBible);
		}, $rawBibles ?: []);
	}
}