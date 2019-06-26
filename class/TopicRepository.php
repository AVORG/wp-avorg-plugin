<?php

namespace Avorg;

use function defined;
use Exception;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class TopicRepository
{
	/** @var AvorgApi $api */
	private $api;

	/** @var Factory $factory */
	private $factory;

	public function __construct(AvorgApi $api, Factory $factory)
	{
		$this->api = $api;
		$this->factory = $factory;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getTopics()
	{
		$rawTopics = $this->api->getTopics() ?: [];

		return array_map([$this, "makeTopic"], $rawTopics);
	}

	/**
	 * @param $topicId
	 * @return mixed
	 * @throws ReflectionException
	 */
	public function getTopic($topicId)
	{
		$rawTopic = $this->api->getTopic($topicId);

		return $this->makeTopic($rawTopic);
	}

	/**
	 * @param $rawTopic
	 * @return mixed
	 * @throws ReflectionException
	 */
	private function makeTopic($rawTopic)
	{
		return $this->factory->make("Avorg\\DataObject\\Topic")->setData($rawTopic);
	}
}