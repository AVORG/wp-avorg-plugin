<?php

final class TestRssLatest extends Avorg\TestCase
{
	/** @var \Avorg\Endpoint\RssEndpoint\RssLatest $rssLatest */
	protected $rssLatest;

	public function setUp()
	{
		parent::setUp();

		$this->rssLatest = $this->factory->get("Endpoint\\RssEndpoint\\RssLatest");
	}

	public function testGetsLatestRecordings()
	{
		$this->rssLatest->getOutput();

		$this->mockAvorgApi->assertMethodCalled("getPresentations");
	}

	public function testPassesPresentationsToView()
	{
		$this->mockAvorgApi->loadPresentations(["presentation"]);

		$this->rssLatest->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-feed.twig", function($call) {
			return $call->recordings[0] instanceof \Avorg\Presentation;
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