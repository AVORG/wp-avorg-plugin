<?php


use Avorg\Endpoint\RssEndpoint\Trending;

final class TestRssTrending extends Avorg\TestCase
{
	/** @var Trending $endpoint */
	protected $endpoint;

	public function setUp(): void
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\RssEndpoint\\Trending");
	}

	public function testGetsTrendingRecordings()
	{
		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getRecordings", "popular");
	}

	public function testAccessors()
	{
		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithData("page-feed.twig", [
			"title" => "AudioVerse Trending Recordings",
			"subtitle" => "Recently-popular recordings at AudioVerse"
		]);
	}
}