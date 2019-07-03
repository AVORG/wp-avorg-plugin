<?php

namespace Avorg;

use natlib\Factory;
use ReflectionException;

if (!\defined('ABSPATH')) exit;

class EndpointFactory
{
	/** @var Factory $factory */
	private $factory;

	private $classes = [
		"Avorg\\Endpoint\\Recording",
		"Avorg\\Endpoint\\RssEndpoint\\RssLatest",
		"Avorg\\Endpoint\\RssEndpoint\\RssSpeaker"
	];

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	/**
	 * @return array
	 */
	public function getEndpoints()
	{
		return array_map([$this, "getEndpointByClass"], $this->classes);
	}

	/**
	 * @param $class
	 * @return mixed
	 * @throws ReflectionException
	 */
	public function getEndpointByClass($class)
	{
		return $this->factory->secure($class);
	}

	public function getEndpointById($id)
	{
		if (strpos($id, "Avorg_Endpoint_") !== 0) return null;

		$class = str_replace("_", "\\", $id);

		if (!class_exists($class)) return null;

		return $this->factory->secure($class);
	}
}