<?php

namespace Avorg;

use Avorg\Route\PageRoute;

if (!\defined('ABSPATH')) exit;


class RouteFactory
{
	public function getPageRoute($pageId, $routeFormat)
	{
		$route = new PageRoute();

		return $route->setPageId($pageId)->setRoute($routeFormat);
	}
}