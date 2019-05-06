<?php

final class TestPageFactory extends Avorg\TestCase
{
	/** @var \Avorg\PageFactory $pageFactory */
	protected $pageFactory;

	public function setUp()
	{
		parent::setUp();

		$this->pageFactory = $this->factory->obtain("PageFactory");
	}

	public function testGetsAllPages()
	{
		$pages = $this->pageFactory->getPages();

		$this->assertTrue(array_reduce($pages, function($carry, $page) {
			return $carry || $page instanceof Avorg\Page\Media;
		}, False));
	}

	public function testPagesHaveDefaultTitleAndContent()
	{
		$pages = $this->pageFactory->getPages();

		$this->assertNotEmpty($pages, "No pages to test");

		array_walk($pages, function(\Avorg\Page $page) {
			$page->createPage();
		});

		$this->mockWordPress->assertNoCallsMatch("wp_insert_post", function($call) {
			$postArray = $call[0];
			return (! $postArray["post_content"]) || (! $postArray["post_title"]);
		});
	}

	public function testPagesHaveTwigTemplate()
	{
		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$pages = $this->pageFactory->getPages();

		$this->assertNotEmpty($pages, "No pages to test");

		array_walk($pages, function(\Avorg\Page $page) {
			$page->addUi("Hello World");
		});

		$this->mockTwig->assertNoCallsMatch("render", function($call) {
			return ! $call[0];
		});
	}

	public function testPageTemplatesExist()
	{
		$this->mockWordPress->setReturnValue("get_option", 5);
		$this->mockWordPress->setReturnValue("get_the_ID", 5);

		$pages = $this->pageFactory->getPages();

		$this->assertNotEmpty($pages, "No pages to test");

		array_walk($pages, function(\Avorg\Page $page) {
			$page->addUi("Hello World");
		});

		$calls = $this->mockTwig->getCalls("render");
		$templates = array_map(function($call) { return $call[0]; }, $calls);

		array_walk($templates, function($template) {
			$this->assertFileExists(AVORG_BASE_PATH . "/view/$template");
		});
	}
}