<?php

namespace Avorg\DataObjectRepository;

use Avorg\DataObject;
use Avorg\DataObjectRepository;
use function defined;
use Exception;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class TopicRepository extends DataObjectRepository
{
	protected $dataObjectClass = "Avorg\\DataObject\\Topic";

    /**
     * @param null $search
     * @param null $start
     * @return array
     * @throws Exception
     */
	public function getDataObjects($search = null, $start = null)
	{
		$rawTopics = $this->api->getTopics($search, $start);

		return $this->makeDataObjects($rawTopics);
	}

	/**
	 * @param $topicId
	 * @return DataObject
	 * @throws ReflectionException
	 * @throws Exception
	 */
	public function getTopic($topicId)
	{
		$rawTopic = $this->api->getTopic($topicId);

		return $this->makeDataObject($rawTopic);
	}
}