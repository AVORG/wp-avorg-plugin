<?php

namespace Avorg\DataObjectRepository;


use Avorg\DataObjectRepository;
use Exception;

if (!defined('ABSPATH')) exit;

class SeriesRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Series";

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getDataObjects($search = null, $start = null)
	{
		$rawObjects = $this->api->getAllSeries($search, $start);

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
}