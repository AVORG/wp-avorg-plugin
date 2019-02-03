<?php

final class TestPlaylistPage extends Avorg\TestCase
{
	/** @var \Avorg\Page\Playlist $playlistPage */
	protected $playlistPage;

	protected function setUp()
	{
		parent::setUp();

		$this->playlistPage = $this->factory->get("Page\\Playlist");
	}

	public function testExist()
	{
		$this->assertInstanceOf("\\Avorg\\Page\\Playlist", $this->playlistPage);
	}

	public function testRendersCorrectTemplate()
	{
		$this->mockWordPress->setCurrentPageToPage($this->playlistPage);

		$this->playlistPage->addUi("content");

		$this->mockTwig->assertTwigTemplateRendered("page-playlist.twig");
	}

	public function testDefaultContentAndTitleSet()
	{
		$this->mockWordPress->setReturnValue("get_option", false);

		$this->playlistPage->createPage();

		$this->mockWordPress->assertPageCreated(
			"Playlist Detail",
			"Playlist Detail"
		);
	}

	public function testPassesTitleThrough()
	{
		$title = $this->playlistPage->setTitle("Title");

		$this->assertEquals("Title", $title);
	}

	public function testGetsPlaylistById()
	{
		$this->mockWordPress->setCurrentPageToPage($this->playlistPage);
		$this->mockWordPress->setReturnValue("get_query_var", 7);

		$this->playlistPage->addUi("");

		$this->mockAvorgApi->assertMethodCalledWith("getPlaylist", 7);
	}

	public function testReturnsPresentations()
	{
		$this->mockWordPress->setCurrentPageToPage($this->playlistPage);
		$this->mockAvorgApi->setReturnValue("getPlaylist", json_decode(json_encode([
			"recordings" => [
				"recording"
			]
		])));

		$this->playlistPage->addUi("");

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-playlist.twig", function($data) {
			return is_a($data->recordings[0], "\\Avorg\\Presentation");
		});
	}
}