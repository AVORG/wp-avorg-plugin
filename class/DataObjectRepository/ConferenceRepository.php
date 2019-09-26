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
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getDataObjects($search = null, $start = null)
	{
		$rawObjects = $this->api->getConferences($search, $start);

		return $this->makeDataObjects($rawObjects);
	}
}