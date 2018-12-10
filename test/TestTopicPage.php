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
		$this->topicPage = $this->factory->getTopicPage();
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
}