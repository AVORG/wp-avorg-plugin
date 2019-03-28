<?php

namespace Avorg;


if (!\defined('ABSPATH')) exit;

abstract class Endpoint
{
	/** @var RouteFactory $routeFactory */
	private $routeFactory;

	protected $routeFormat;

	public function __construct(RouteFactory $routeFactory)
	{
		$this->routeFactory = $routeFactory;
	}

	abstract public function getOutput();

	public function getRoute()
	{
		return $this->routeFactory->getEndpointRoute(
			$this->getId(),
			$this->routeFormat
		);
	}

	/**
	 * @return string
	 */
	private function getId()
	{
		return str_replace("\\", "_", get_class($this));
	}
}