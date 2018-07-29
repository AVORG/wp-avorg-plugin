<?php

final class TestFilesystem extends Avorg\TestCase
{
	public function testExists()
	{
		$f = new \Avorg\Filesystem();
		
		$this->assertTrue(method_exists($f, "getFile"));
	}
}