<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObject;
use Avorg\DataObjectRepository;
use Exception;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class SponsorRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Sponsor";

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getDataObjects($search = null, $start = null)
	{
		$rawObjects = $this->api->getSponsors($search, $start);

		return $this->makeDataObjects($rawObjects);
	}

	/**
	 * @param $id
	 * @return DataObject
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function getSponsor($id)
	{
		$rawObject = $this->api->getSponsor($id);

		return $this->makeDataObject($rawObject);
	}
}