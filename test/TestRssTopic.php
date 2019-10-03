<?php


use Avorg\DataObject\Recording;
use Avorg\Endpoint\RssEndpoint\Topic;

final class TestRssTopic extends Avorg\TestCase
{
	/** @var RssTopic $endpoint */
	protected $endpoint;

	public function setUp()
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\RssEndpoint\\Topic");
	}

	public function testGetsEntityId()
	{
		$this->endpoint->getOutput();

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
	}

	public function testGetsRecordings()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "5");

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getTopicRecordings", "5");
	}

	public function testPassesRecordingsToView()
	{
		$this->mockAvorgApi->loadTopicRecordings(["recording"]);

		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-feed.twig", function($call) {
			return $call->recordings[0] instanceof Recording;
		});
	}

	public function testAccessors()
	{
		$this->mockAvorgApi->loadtopic([
			"title" => "Testimonies (Personal)"
		]);

		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithData("page-feed.twig", [
			"title" => "Testimonies (Personal) — AudioVerse Latest Recordings",
			"subtitle" => "The latest recordings at AudioVerse"
		]);
	}
}