<?php

namespace Avorg;

class StubPhp extends Php
{
	use Stub;

	public function array_rand(...$arguments)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function header($string)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function doDie()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}
}