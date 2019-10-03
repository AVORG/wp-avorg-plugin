<?php

use Avorg\DataObject\Recording;
use Avorg\Endpoint\RssEndpoint\Latest;

final class TestRssLatest extends Avorg\TestCase
{
	/** @var Latest $endpoint */
	protected $endpoint;

	public function setUp(): void
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\RssEndpoint\\Latest");
	}

	public function testGetsLatestRecordings()
	{
		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalled("getRecordings");
	}

	public function testPassesRecordingsToView()
	{
		$this->mockAvorgApi->loadRecordings(["recording"]);

		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-feed.twig", function($call) {
			return $call->recordings[0] instanceof Recording;
		});
	}

	public function testAccessors()
	{
		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithData("page-feed.twig", [
			"title" => "AudioVerse Latest Recordings",
			"subtitle" => "The latest recordings at AudioVerse"
		]);
	}
}