<?php

use Avorg\Page\Playlist\Detail;

final class TestPlaylistDetail extends Avorg\TestCase
{
	/** @var Detail $page */
	protected $page;

	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->page = $this->factory->secure("Avorg\\Page\\Playlist\\Detail");
	}

	public function testExist()
	{
		$this->assertInstanceOf("\\Avorg\\Page\\Playlist\\Detail", $this->page);
	}

	public function testRendersCorrectTemplate()
	{
		$this->mockWordPress->setCurrentPageToPage($this->page);

		$this->page->addUi("content");

		$this->mockTwig->assertTwigTemplateRendered("page-playlist.twig");
	}

	public function testDefaultContentAndTitleSet()
	{
		$this->mockWordPress->setReturnValue("get_option", false);

		$this->page->createPage();

		$this->mockWordPress->assertPageCreated(
			"Playlist Detail",
			"Playlist Detail"
		);
	}

	public function testPassesTitleThrough()
	{
		$title = $this->page->filterTitle("Title");

		$this->assertEquals("Title", $title);
	}

	public function testGetsPlaylistById()
	{
		$this->mockWordPress->setCurrentPageToPage($this->page);
		$this->mockWordPress->setReturnValue("get_query_var", 7);

		$this->page->addUi("");

		$this->mockAvorgApi->assertMethodCalledWith("getPlaylist", 7);
	}

	public function testReturnsRecordings()
	{
		$this->mockWordPress->setCurrentPageToPage($this->page);
		$this->mockAvorgApi->setReturnValue("getPlaylist", json_decode(json_encode([
			"recordings" => [
				$this->arrayToObject(["id" => "0"])
			]
		])));

		$this->page->addUi("");

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-playlist.twig", function($data) {
		    $recordings = $data->recordings;
		    $recording = reset($recordings);

			return is_a($recording, "\\Avorg\\DataObject\\Recording");
		});
	}

	public function testFilterTitle()
	{
		$this->mockAvorgApi->loadPlaylist([
			"title" => "the_title"
		]);

		$result = $this->page->filterTitle("");

		$this->assertEquals("the_title - AudioVerse", $result);
	}
}