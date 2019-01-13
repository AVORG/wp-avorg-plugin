<?php

namespace Avorg;

class StubWordPress extends WordPress
{
	use Stub {
		handleCall as protected traitHandleCall;
	}

	public function __call( $function, $arguments )
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}