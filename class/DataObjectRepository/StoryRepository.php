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
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getDataObjects($search = null, $start = null)
	{
		$rawObjects = $this->api->getStories($search, $start);

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
}