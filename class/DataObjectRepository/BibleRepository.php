<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObject\Bible;
use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class BibleRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Bible";

	/**
	 * @param $id
	 * @return Bible|null
	 * @throws Exception
	 */
	public function getBible($id)
	{
		$rawBibles = $this->api->getBibles();

		return array_key_exists($id, $rawBibles) ? $rawBibles[$id] : null;
	}

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