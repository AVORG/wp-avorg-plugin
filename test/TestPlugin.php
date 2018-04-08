<?php

final class TestPlugin extends Avorg\TestCase
{
	public function testPluginClassExists()
	{
		$this->assertTrue( class_exists( "Avorg\\Plugin" ) );
	}
	
	public function testHasActivateMethod()
	{
		$t = new Avorg\Plugin;
		$this->assertTrue( method_exists( $t, "activate" ) );
	}
}