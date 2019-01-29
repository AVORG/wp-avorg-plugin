<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class EndpointFactory
{
	/** @var Factory $factory */
	private $factory;

	private $endpointNames = [
		"RssEndpoint"
	];

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	public function getEndpoints()
	{
		return array_map(function($endpointName) {
			return $this->factory->get("Endpoint\\$endpointName");
		}, $this->endpointNames);
	}

	public function getEndpoint($id)
	{
		if (!in_array($id, $this->endpointNames)) return null;

		return $this->factory->get("Endpoint\\$id");
	}
}