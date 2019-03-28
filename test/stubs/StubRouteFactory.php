<?php

namespace Avorg;

class StubRouteFactory extends RouteFactory
{
	use Stub;

	public function getPageRoute($pageId, $routeFormat)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}