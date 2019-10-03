<?php

namespace Avorg;

use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

abstract class DataObjectRepository
{
	/** @var AvorgApi $api */
	protected $api;

	/** @var Factory $factory */
	protected $factory;

	protected $dataObjectClass;

	public function __construct(AvorgApi $api, Factory $factory)
	{
		$this->api = $api;
		$this->factory = $factory;
	}

	/**
	 * @param $rawObjects
	 * @return array
	 */
	protected function makeDataObjects($rawObjects)
	{
		return array_map([$this, "makeDataObject"], (array) $rawObjects);
	}

	/**
	 * @param $rawObject
	 * @return DataObject
	 * @throws ReflectionException
	 */
	protected function makeDataObject($rawObject)
	{
		return $this->factory->make($this->dataObjectClass)->setData($rawObject);
	}
}