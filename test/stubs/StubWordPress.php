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

	/**
	 * @param $method
	 * @param $args
	 * @return mixed|null
	 */
	public function handleCall($method, $args)
	{
		if ($method !== "__call") return $this->traitHandleCall($method, $args);

		$wpMethod = $args[0];
		$wpArgs = $args[1];

		return $this->traitHandleCall($wpMethod, $wpArgs);
	}

	public function assertWordPressFunctionCalled($function)
	{
		$this->assertMethodCalled($function);
	}

	public function assertWordPressFunctionCalledWith($function, ...$arguments)
	{
		$this->assertMethodCalledWith($function, ...$arguments);
	}
}