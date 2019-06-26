<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class BibleRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Bible";

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getBibles()
	{
		$rawBibles = $this->api->getBibles();

		return $this->makeDataObjects($rawBibles);
	}
}