<?php

use Avorg\Page\Topic\Detail;

final class TestTopicDetail extends Avorg\TestCase
{
	/** @var Detail $topicPage */
	protected $topicPage;

	private $topicPageInsertCall = array("wp_insert_post", array(
		"post_content" => "Topic Detail",
		"post_title" => "Topic Detail",
		"post_status" => "publish",
		"post_type" => "page"
	), true);

	protected function setUp()
	{
		parent::setUp();

		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);
		$this->mockWordPress->setReturnValues("get_query_var",  5);
		$this->topicPage = $this->factory->secure("Avorg\\Page\\Topic\\Detail");
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Page\\Topic\\Detail", $this->topicPage);
	}

	public function testHasPageIdOptionName()
	{
		$this->mockWordPress->setReturnValue("get_option", false);
		$this->mockWordPress->setReturnValue("get_post_status", false);
		$this->mockWordPress->setReturnValue("wp_insert_post", false);

		$this->topicPage->createPage();

		$this->mockWordPress->assertMethodCalledWith(
			"update_option",
			"avorg_page_id_avorg_page_topic_detail",
			false
		);
	}

	public function testInsertsPageWithDefaultContentAndTitle()
	{
		$this->mockWordPress->setReturnValue("get_option", false);
		$this->mockWordPress->setReturnValue("get_post_status", false);
		$this->mockWordPress->setReturnValue("wp_insert_post", false);

		$this->topicPage->createPage();

		$this->mockWordPress->assertMethodCalledWith(
			...$this->topicPageInsertCall
		);
	}

	public function testSetTitleReturnsTitle()
	{
		$title = $this->topicPage->filterTitle("title");

		$this->assertEquals("title", $title);
	}

	public function testUsesTwigTemplate()
	{
		$this->topicPage->addUi("content");

		$this->mockTwig->assertTwigTemplateRendered("page-topic.twig");
	}

	public function testGetsTopicId()
	{
		$this->topicPage->addUi("content");

		$this->mockWordPress->assertMethodCalledWith( "get_query_var", "entity_id");
	}

	public function testGetsRecordings()
	{
		$this->mockWordPress->passCurrentPageCheck();
		$this->mockWordPress->setReturnValues("get_query_var",  10);

		$this->topicPage->addUi("content");

		$this->mockAvorgApi->assertMethodCalledWith( "getTopicRecordings", 10);
	}

	public function testUsesRecordingsToRender()
	{
		$this->mockAvorgApi->setReturnValue("getTopicRecordings", []);

		$this->topicPage->addUi("content");

		$this->mockTwig->assertTwigTemplateRenderedWithData(
			"page-topic.twig",
			["recordings" => []]
		);
	}

	public function testGetsWrappedRecordings()
	{
		$this->mockAvorgApi->setReturnValue("getTopicRecordings", [
			[ "recordings" => "RECORDING" ]
		]);

		$this->topicPage->addUi("content");

		$calls = $this->mockTwig->getCalls("render");

		$dataObject = $calls[0][1]["avorg"];
		$recordings = $dataObject->recordings;
		$recording = $recordings[0];

		$this->assertInstanceOf("\\Avorg\\DataObject\\Recording", $recording);
	}

	public function testSetsTitle()
	{
		$this->mockAvorgApi->loadTopic([
			"title" => "Agriculture"
		]);

		$result = $this->topicPage->filterTitle("");

		$this->assertEquals("Agriculture - AudioVerse", $result);
	}
}