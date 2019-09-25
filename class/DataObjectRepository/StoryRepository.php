<?php

namespace Avorg\DataObjectRepository;

use Avorg\DataObject;
use Avorg\DataObjectRepository;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class StoryRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Story";

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getStories()
	{
		$rawObjects = $this->api->getStories();

		return $this->makeDataObjects($rawObjects);
	}

	/**
	 * @param $id
	 * @return DataObject
	 * @throws Exception
	 */
	public function getStory($id)
	{
		$rawObject = $this->api->getBook($id);

		return $this->makeDataObject($rawObject);
	}

    public function getDataObjects($search = null, $start = null)
    {
        // TODO: Implement getDataObjects() method.
    }
}