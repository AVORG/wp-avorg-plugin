<?php

final class TestFeed extends Avorg\TestCase
{
	/** @var \Avorg\Feed $feed */
	protected $feed;

	public function setUp()
	{
		parent::setUp();

		$this->feed = $this->factory->make("Feed");
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Feed", $this->feed);
	}

	public function testsReturnsRenderedTemplate()
	{
		$this->mockTwig->setReturnValue("render", "compiled_twig");

		$result = $this->feed->toXml();

		$this->assertEquals("compiled_twig", $result);
	}

	/**
	 * @dataProvider dataSetScenarioProvider
	 * @param $function
	 * @param $args
	 * @param $expectedData
	 */
	public function testSetData($function, $args, $expectedData)
	{
		$this->feed->$function(...$args);

		$this->feed->toXml();

		$this->mockTwig->assertTwigTemplateRenderedWithData("page-feed.twig", $expectedData);
	}

	public function dataSetScenarioProvider()
	{
		return [
			"Set Recordings" =>	[
				"setRecordings",
				[["recording"]],
				["recordings" => ["recording"]]
			],
			"Set Title" => [
				"setTitle",
				["title"],
				["title" => "title"]
			],
			"Set Link" => [
				"setLink",
				["url"],
				["link" => "url"]
			],
			"Set Language" => [
				"setLanguage",
				["en"],
				["language" => "en"]
			],
			"Set Image" => [
				"setImage",
				["image_url"],
				["image" => "image_url"]
			]
		];
	}
}