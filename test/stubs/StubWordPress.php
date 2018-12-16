<?php

namespace Avorg;

class StubWordPress extends WordPress
{
	use Stub;

	public function call( $function, ...$arguments )
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}