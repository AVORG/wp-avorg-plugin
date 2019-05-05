<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class EndpointFactory
{
	/** @var Factory $factory */
	private $factory;

	private $endpointNames = [
		"PresentationEndpoint",
		"RssEndpoint\\RssLatest",
		"RssEndpoint\\RssSpeaker"
	];

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	public function getEndpoints()
	{
		return array_map(function($endpointName) {
			return $this->factory->secure("Endpoint\\$endpointName");
		}, $this->endpointNames);
	}

	public function getEndpoint($id)
	{
		if (strpos($id, "Avorg_Endpoint_") !== 0) return null;

		$class = str_replace("_", "\\", $id);

		if (!class_exists($class)) return null;

		return $this->factory->secure($class);
	}
}