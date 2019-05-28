<?php

namespace Avorg;

use Avorg\Route\EndpointRoute;
use Avorg\Route\PageRoute;
use natlib\Factory;

if (!\defined('ABSPATH')) exit;


class RouteFactory
{
	/** @var Factory $factory */
	private $factory;

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	public function getPageRoute($pageId, $routeFormat)
	{
		/** @var PageRoute $route */
		$route = $this->factory->obtain("Avorg\\Route\\PageRoute");

		return $route->setPageId($pageId)->setFormat($routeFormat);
	}

	public function getEndpointRoute($endpointId, $routeFormat)
	{
		/** @var EndpointRoute $route */
		$route = $this->factory->obtain("Avorg\\Route\\EndpointRoute");

		return $route->setEndpointId($endpointId)->setFormat($routeFormat);
	}
}