<?php

use Avorg\TestCase;

final class TestPlaylist extends Avorg\TestCase
{
	public function testGetUrl()
	{
		$playlist = $this->makePlaylist([
			"id" => "9",
			"title" => "Powerful Personal Testimonies",
		]);

		$this->assertEquals(
			"http://${_SERVER['HTTP_HOST']}/english/playlists/lists/9/powerful-personal-testimonies.html",
			$playlist->getUrl()
		);
	}
}