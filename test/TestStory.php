<?php

final class TestStory extends Avorg\TestCase
{
	public function testGetUrl()
	{
		$playlist = $this->makeStory([
			"id" => "1167",
			"title" => "Acts of the Apostles",
		]);

		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/audiobooks/books/1167/acts-of-the-apostles.html",
			$playlist->getUrl()
		);
	}
}