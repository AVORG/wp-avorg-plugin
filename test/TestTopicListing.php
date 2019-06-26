<?php

final class TestTopicListing extends Avorg\TestCase
{
	/** @var Avorg\Page\Topic\Listing $topicListing */
	private $topicListing;

	public function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->topicListing = $this->factory->make("Avorg\\Page\\Topic\\Listing");
	}

	public function testExists()
	{
		$this->mockAvorgApi->loadTopics([]);

		$this->assertTwigGlobalMatchesCallback($this->topicListing, function($avorg) {
			return $avorg->topics[0] instanceof \Avorg\DataObject\Topic;
		});
	}

	public function testGetsTopics()
	{
		$this->topicListing->addUi("");

		$this->mockAvorgApi->assertMethodCalled("getTopics");
	}

	public function testAvorgIncludesTopicData()
	{
		$this->mockAvorgApi->loadTopics(["title" => "Agriculture"]);

		$this->assertTwigGlobalMatchesCallback($this->topicListing, function($avorg) {
			return $avorg->topics[0]->title === "Agriculture";
		});
	}
}