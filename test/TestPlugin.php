<?php

final class TestPlugin extends Avorg\TestCase
{
	public function testPluginClassExists()
	{
		$this->assertTrue( class_exists( "Avorg\\Plugin" ) );
	}
	
	public function testHasActivateMethod()
	{
		$t = new Avorg\Plugin( $this->mockWordPress );
		$this->assertTrue( method_exists( $t, "activate" ) );
	}
	
	public function testInsertsMediaDetailsPage() {
		$this->mockedPlugin->activate();

		$this->assertCalledWith( $this->mockWordPress, "call", "wp_insert_post", array(
			"ID" => 100,
			"post_content" => "Media Detail",
			"post_title" => "Media Detail",
			"post_status" => "publish",
			"post_type" => "page"
		) );
	}
}