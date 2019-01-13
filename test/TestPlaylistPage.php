<?php

final class TestPlaylistPage extends Avorg\TestCase
{
	/** @var \Avorg\Page\Playlist $playlistPage */
	protected $playlistPage;

	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->setReturnValue("call", 5);
		$this->playlistPage = $this->factory->get("Page\\Playlist");
	}

	public function testExist()
	{
		$this->assertInstanceOf("\\Avorg\\Page\\Playlist", $this->playlistPage);
	}

/*	public function testRendersCorrectTemplate()
	{
		$this->playlistPage->addUi("content");

		$this->assertTwigTemplateRendered();
	}*/
}