<?php

final class TestPlugin extends Avorg\TestCase
{
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