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
	 * @return array
	 * @throws Exception
	 */
	public function getTopics()
	{
		$rawTopics = $this->api->getTopics();

		return $this->makeDataObjects($rawTopics);
	}

	/**
	 * @param $topicId
	 * @return DataObject
	 * @throws ReflectionException
	 */
	public function getTopic($topicId)
	{
		$rawTopic = $this->api->getTopic($topicId);

		return $this->makeDataObject($rawTopic);
	}
}