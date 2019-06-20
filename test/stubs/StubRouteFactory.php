<?php

namespace Avorg;

class StubRouteFactory extends RouteFactory
{
	use Stub;

	public function getPageRoute($routeId, $routeFormat)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}