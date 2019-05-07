<?php

final class TestTopicPage extends Avorg\TestCase
{
	/** @var \Avorg\Page\Topic $topicPage */
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
		$this->topicPage = $this->factory->secure("Page\\Topic");
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Page\\Topic", $this->topicPage);
	}

	public function testHasPageIdOptionName()
	{
		$this->mockWordPress->setReturnValue("get_option", false);
		$this->mockWordPress->setReturnValue("get_post_status", false);
		$this->mockWordPress->setReturnValue("wp_insert_post", false);

		$this->topicPage->createPage();

		$this->mockWordPress->assertMethodCalledWith(
			"update_option",
			"avorg_page_id_avorg_page_topic",
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

		$this->mockTwig->assertTwigTemplateRendered("organism-topic.twig");
	}

	public function testGetsTopicId()
	{
		$this->topicPage->addUi("content");

		$this->mockWordPress->assertMethodCalledWith( "get_query_var", "entity_id");
	}

	public function testGetsPresentations()
	{
		$this->mockWordPress->setReturnValue("get_option", 10);
		$this->mockWordPress->setReturnValue("get_the_ID", 10);
		$this->mockWordPress->setReturnValues("get_query_var",  10);

		$this->topicPage->addUi("content");

		$this->mockAvorgApi->assertMethodCalledWith( "getTopicPresentations", 10);
	}

	public function testUsesPresentationsToRender()
	{
		$this->mockAvorgApi->setReturnValue("getTopicPresentations", []);

		$this->topicPage->addUi("content");

		$this->mockTwig->assertTwigTemplateRenderedWithData(
			"organism-topic.twig",
			["recordings" => []]
		);
	}

	public function testGetsWrappedRecordings()
	{
		$this->mockAvorgApi->setReturnValue("getTopicPresentations", [
			[ "recordings" => "RECORDING" ]
		]);

		$this->topicPage->addUi("content");

		$calls = $this->mockTwig->getCalls("render");

		$dataObject = $calls[0][1]["avorg"];
		$recordings = $dataObject->recordings;
		$recording = $recordings[0];

		$this->assertInstanceOf("\\Avorg\\Presentation", $recording);
	}
}