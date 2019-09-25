<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObject;
use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class BibleRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Bible";

	/**
	 * @param $id
	 * @return DataObject|null
	 * @throws Exception
	 */
	public function getBible($id)
	{
		$rawBibles = (array) $this->api->getBibles();
		$rawBible = array_key_exists($id, $rawBibles) ? $rawBibles[$id] : null;

		return $rawBible ? $this->makeDataObject($rawBible) : null;
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

    public function getDataObjects()
    {
        // TODO: Implement getDataObjects() method.
    }
}