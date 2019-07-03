<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class ConferenceRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Conference";

	/**
	 * @param $id
	 * @return mixed
	 * @throws Exception
	 */
	public function getConference($id)
	{
		$rawObjects = (array) $this->api->getConferences();
		$filteredObjects = array_filter($rawObjects, function($rawObject) use($id) {
			return $rawObject->id = $id;
		});

		return $this->makeDataObject(reset($filteredObjects));
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getConferences()
	{
		$rawObjects = $this->api->getConferences();

		return $this->makeDataObjects($rawObjects);
	}
}