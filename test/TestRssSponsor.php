<?php


use Avorg\DataObject\Recording;
use Avorg\Endpoint\RssEndpoint\RssSponsor;

final class TestRssSponsor extends Avorg\TestCase
{
	/** @var RssSponsor $endpoint */
	protected $endpoint;

	public function setUp()
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\RssEndpoint\\RssSponsor");
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

		$this->mockAvorgApi->assertMethodCalledWith("getSponsorRecordings", "5");
	}

	public function testPassesRecordingsToView()
	{
		$this->mockAvorgApi->loadSponsorRecordings([]);

		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-feed.twig", function($call) {
			return $call->recordings[0] instanceof Recording;
		});
	}

	public function testUsesSponsorData()
	{
		$this->mockAvorgApi->loadSponsor([
			"title" => "sponsor_title",
			"photo256" => "image_url",
		]);

		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithData("page-feed.twig", [
			"title" => "sponsor_title Sermons",
			"subtitle" => "The latest AudioVerse sermons sponsored by sponsor_title",
			"image" => "image_url"
		]);
	}

	public function testLimitsRecordingsToOneHundred()
	{
		$dataArrays = array_fill(0, 300, []);

		$this->mockAvorgApi->loadSponsorRecordings(...$dataArrays);

		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-feed.twig", function($call) {
			return count($call->recordings) === 100;
		});
	}
}