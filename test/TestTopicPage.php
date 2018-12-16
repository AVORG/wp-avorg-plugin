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

		$this->mockWordPress->setReturnValue("call", 5);
		$this->topicPage = $this->factory->getPage_Topic();
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Page\\Topic", $this->topicPage);
	}

	public function testHasPageIdOptionName()
	{
		$this->mockWordPress->setReturnValue("call", false);

		$this->topicPage->createPage();

		$this->assertCalledWith(
			$this->mockWordPress,
			"call",
			"update_option",
			"avorgTopicPageId",
			false
		);
	}

	public function testInsertsPageWithDefaultContentAndTitle()
	{
		$this->mockWordPress->setReturnValue("call", false);

		$this->topicPage->createPage();

		$this->assertCalledWith(
			$this->mockWordPress,
			"call",
			...$this->topicPageInsertCall
		);
	}

	public function testSetTitleReturnsTitle()
	{
		$title = $this->topicPage->setTitle("title");

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

		$this->mockWordPress->assertMethodCalledWith("call", "get_query_var", "topic_id");
	}

	public function testGetsPresentations()
	{
		$this->mockWordPress->setReturnValue("call", 10);

		$this->topicPage->addUi("content");

		$this->assertCalledWith($this->mockAvorgApi, "getTopicPresentations", 10);
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