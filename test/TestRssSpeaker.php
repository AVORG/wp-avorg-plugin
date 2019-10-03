<?php


use Avorg\DataObject\Recording;
use Avorg\Endpoint\RssEndpoint\Speaker;

final class TestRssSpeaker extends Avorg\TestCase
{
	/** @var RssSpeaker $endpoint */
	protected $endpoint;

	public function setUp()
	{
		parent::setUp();

		$this->endpoint = $this->factory->secure("Avorg\\Endpoint\\RssEndpoint\\Speaker");
	}

	public function testGetsSpeakerId()
	{
		$this->endpoint->getOutput();

		$this->mockWordPress->assertMethodCalledWith("get_query_var", "entity_id");
	}

	public function testGetsRecordings()
	{
		$this->mockWordPress->setReturnValue("get_query_var", "5");

		$this->endpoint->getOutput();

		$this->mockAvorgApi->assertMethodCalledWith("getPresenterRecordings", "5");
	}

	public function testPassesRecordingsToView()
	{
		$this->mockAvorgApi->loadPresenterRecordings(["recording"]);

		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithDataMatching("page-feed.twig", function($call) {
			return $call->recordings[0] instanceof Recording;
		});
	}

	public function testUsesPresenterData()
	{
		$this->mockAvorgApi->loadPresenter([
			"givenName" => "first",
			"surname" => "last",
			"suffix" => "suffix",
			"photo256" => "image_url"
		]);

		$this->endpoint->getOutput();

		$this->mockTwig->assertTwigTemplateRenderedWithData("page-feed.twig", [
			"title" => "Sermons by first last suffix",
			"subtitle" => "The latest AudioVerse sermons by first last suffix",
			"image" => "image_url"
		]);
	}
}