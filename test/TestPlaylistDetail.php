<?php

final class TestPlaylistDetail extends Avorg\TestCase
{
	/** @var \Avorg\Page\Playlist\Detail $playlistPage */
	protected $playlistPage;

	protected function setUp()
	{
		parent::setUp();

		$this->playlistPage = $this->factory->secure("Avorg\\Page\\Playlist\\Detail");
	}

	public function testExist()
	{
		$this->assertInstanceOf("\\Avorg\\Page\\Playlist\\Detail", $this->playlistPage);
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
		$title = $this->playlistPage->filterTitle("Title");

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
				$this->convertArrayToObjectRecursively(["id" => "0"])
			]
		])));

		$this->playlistPage->addUi("");

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-playlist.twig", function($data) {
			return is_a($data->recordings[0], "\\Avorg\\Presentation");
		});
	}
}