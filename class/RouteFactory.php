<?php

namespace Avorg;

use Avorg\Route\EndpointRoute;
use Avorg\Route\PageRoute;

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
		$route = $this->factory->obtain("Route\\PageRoute");

		return $route->setPageId($pageId)->setFormat($routeFormat);
	}

	public function getEndpointRoute($endpointId, $routeFormat)
	{
		/** @var EndpointRoute $route */
		$route = $this->factory->obtain("Route\\EndpointRoute");

		return $route->setEndpointId($endpointId)->setFormat($routeFormat);
	}
}