<?php

namespace Avorg;

class StubWordPress extends WordPress
{
	use Stub;

	public function call( $function, ...$arguments )
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function assertWordPressFunctionCalled($function)
	{
		$calls = $this->getCalls("call");

		$wasCalled = array_reduce($calls, function ($carry, $call) use ($function) {
			return $carry || $call[0] === $function;
		}, false);

		$error = "Failed to assert $function was called using the WordPress wrapper\r\n\r\n" . json_encode($calls);

		$this->testCase->assertTrue($wasCalled, $error);
	}

	public function assertWordPressFunctionCalledWith($function, ...$arguments)
	{
		$needle = array_merge( [$function], $arguments );
		$calls = $this->getCalls("call");

		$wasCalled = array_reduce($calls, function ($carry, $call) use ($needle) {
			return $carry || $call === $needle;
		}, false);

		$needleHaystack = json_encode($needle);
		$callExport = json_encode($calls);
		$message = "Failed to assert $function was called using specified arguments\r\n\r\nNeedle:\r\n$needleHaystack\r\nHaystack:\r\n$callExport";

		$this->testCase->assertTrue(
			$wasCalled,
			$message
		);
	}
}