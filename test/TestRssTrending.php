<?php


use Avorg\Endpoint\RssEndpoint\RssTrending;

final class TestRssTrending extends Avorg\TestCase
{
	/** @var RssTrending $endpoint */
	protected $endpoint;

	public function setUp()
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\RssEndpoint\\RssTrending");
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