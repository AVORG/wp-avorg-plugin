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
	 * @return array
	 * @throws Exception
	 */
	public function getSponsors()
	{
		$rawObjects = $this->api->getSponsors();

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