<?php

final class TestPlugin extends avorg\TestCase
{
	public function testPluginClassExists()
	{
		$this->assertTrue( class_exists( "avorg\\Plugin" ) );
	}
}