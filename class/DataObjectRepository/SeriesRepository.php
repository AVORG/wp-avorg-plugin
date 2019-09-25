<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class SeriesRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Series";

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getAllSeries()
	{
		$rawObjects = $this->api->getAllSeries();

		return $this->makeDataObjects($rawObjects);
	}

	/**
	 * @param $id
	 * @return \Avorg\DataObject
	 * @throws \ReflectionException
	 */
	public function getOneSeries($id)
	{
		$rawObject = $this->api->getOneSeries($id);

		return $this->makeDataObject($rawObject);
	}

    public function getDataObjects()
    {
        // TODO: Implement getDataObjects() method.
    }
}