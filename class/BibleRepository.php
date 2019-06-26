<?php

namespace Avorg;


if (!defined('ABSPATH')) exit;

class BibleRepository
{
	/** @var AvorgApi $api */
	private $api;

	public function __construct(AvorgApi $api)
	{
		$this->api = $api;
	}

	public function getBibles()
	{
		$this->api->getBibles();
	}
}