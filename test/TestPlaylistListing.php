<?php

use Avorg\DataObject\Playlist;
use Avorg\Page\Playlist\Listing;

final class TestPlaylistListing extends Avorg\TestCase
{
	/** @var Listing $page */
	protected $page;

	/**
	 * @throws ReflectionException
	 */
	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->secure("Avorg\\Page\\Playlist\\Listing");
	}

	public function testGetsRawPlaylists()
	{
		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getPlaylists");
	}

	public function testReturnsPlaylistObjects()
	{
		$this->mockAvorgApi->loadPlaylists([]);

		$this->assertTwigGlobalMatchesCallback($this->page, function($global) {
			return $global->playlists[0] instanceof Playlist;
		});
	}
}