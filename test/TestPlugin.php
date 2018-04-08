<?php

final class TestPlugin extends Avorg\TestCase
{
	public function testInsertsMediaDetailsPage() {
		$this->mockedPlugin->activate();

		$this->assertCalledWith( $this->mockWordPress, "call", "wp_insert_post", array(
			"post_content" => "Media Detail",
			"post_title" => "Media Detail",
			"post_status" => "publish",
			"post_type" => "page"
		), true );
	}
	
	public function testDoesNotInsertPageTwice() {
		$this->mockWordPress->setReturnValue( "call", ["post"] );

		$this->mockedPlugin->activate();

		$this->assertNotCalledWith( $this->mockWordPress, "call", "wp_insert_post", array(
			"post_content" => "Media Detail",
			"post_title" => "Media Detail",
			"post_status" => "publish",
			"post_type" => "page"
		), true );
	}
}