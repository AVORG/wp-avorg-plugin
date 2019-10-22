<?php

namespace Avorg;

use natlib\Stub;

class StubPhp extends Php
{
	use Stub;

	public function arrayRand($array, $num = 1)
	{
		$val = $this->handleCall(__FUNCTION__, func_get_args());

		if ($val) {
		    return $val;
        }

		return parent::arrayRand($array, $num);
	}

	public function header($string)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function doEcho($string)
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function doDie()
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function initSession()
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }
}