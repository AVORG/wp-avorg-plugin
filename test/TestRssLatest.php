<?php

final class TestRssLatest extends Avorg\TestCase
{
	/** @var \Avorg\Endpoint\RssEndpoint\RssLatest $rssLatest */
	protected $rssLatest;

	public function setUp()
	{
		parent::setUp();

		$this->rssLatest = $this->factory->secure("Avorg\\Endpoint\\RssEndpoint\\RssLatest");
	}

	public function testGetsLatestRecordings()
	{
		$this->rssLatest->getOutput();

		$this->mockAvorgApi->assertMethodCalled("getRecordings");
	}

	public function testPassesRecordingsToView()
	{
		$this->mockAvorgApi->loadRecordings(["recording"]);

		$this->rssLatest->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-feed.twig", function($call) {
			return $call->recordings[0] instanceof \Avorg\Recording;
		});
	}

	public function testAccessors()
	{
		$this->rssLatest->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithData("page-feed.twig", [
			"title" => "AudioVerse Latest Recordings",
			"subtitle" => "The latest recordings at AudioVerse"
		]);
	}
}