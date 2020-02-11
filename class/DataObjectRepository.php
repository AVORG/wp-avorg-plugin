<?php

namespace Avorg;

use Exception;
use natlib\Stub;
use ReflectionClass;
use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

abstract class DataObjectRepository
{
	/** @var AvorgApi $api */
	protected $api;

	/** @var Database $database */
    protected $database;

	/** @var Factory $factory */
	protected $factory;

	protected $dataObjectClass;

	public function __construct(AvorgApi $api, Database $database, Factory $factory)
	{
		$this->api = $api;
		$this->database = $database;
		$this->factory = $factory;
	}

	abstract public function getDataObjects($search = null, $start = null);

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
     * @throws Exception
     */
	protected function makeDataObject($rawObject)
	{
	    /** @var DataObject $object */
        $object = $this->factory->make($this->dataObjectClass)->setData($rawObject);

        $this->incrementEntityWeight($object);

        return $object;
	}

    /**
     * @param DataObject $object
     * @throws ReflectionException
     * @throws Exception
     */
    private function incrementEntityWeight(DataObject $object): void
    {
        $this->database->incrementOrCreateWeight(
            $object->getId(),
            $object->getTitle(),
            (new ReflectionClass($object))->getShortName(),
            $object->getUrl()
        );
    }
}