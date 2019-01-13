<?php

namespace Avorg;

class StubWordPress extends WordPress
{
	use Stub {
		handleCall as protected traitHandleCall;
	}

	public function call( $function, ...$arguments )
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
		if ($method !== "call") return $this->traitHandleCall($method, $args);
		
		$wpMethod = array_shift($args);

		return $this->traitHandleCall($wpMethod, $args);
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