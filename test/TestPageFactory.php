<?php

use Avorg\Page;

final class TestPageFactory extends Avorg\TestCase
{
	/** @var \Avorg\PageFactory $pageFactory */
	protected $pageFactory;

	protected $pages;

	public function setUp()
	{
		parent::setUp();

		$this->mockWordPress->passCurrentPageCheck();

		$this->pageFactory = $this->factory->obtain("Avorg\\PageFactory");
		$this->pages = $this->pageFactory->getPages();
	}

	private function assertPagesExist()
	{
		$this->assertTrue((bool) array_filter($this->pages, function($page) {
			return $page instanceof Avorg\Page;
		}), "PageFactory did not return pages");
	}

	public function testPagesHaveDefaultTitleAndContent()
	{
		$this->assertPagesExist();

		$this->mockWordPress->setReturnValue("get_post_status", FALSE);

		array_walk($this->pages, function(Page $page) {
			$page->createPage();
		});

		$this->mockWordPress->assertCallCount("wp_insert_post", count($this->pages));

		$this->mockWordPress->assertNoCallsMatch("wp_insert_post", function($call) {
			$postArray = $call[0];

			return empty($postArray["post_content"]) || empty($postArray["post_title"]);
		});
	}

	public function testPagesHaveTwigTemplate()
	{
		$this->assertPagesExist();

		array_walk($this->pages, function(Page $page) {
			$page->addUi("Hello World");
		});

		$this->mockTwig->assertNoCallsMatch("render", function($call) {
			return ! $call[0];
		});
	}

	public function testPageTemplatesExist()
	{
		$this->assertPagesExist();

		array_walk($this->pages, function(Page $page) {
			$page->addUi("Hello World");
		});

		$calls = $this->mockTwig->getCalls("render");
		$templates = array_map(function($call) { return $call[0]; }, $calls);

		array_walk($templates, function($template) {
			$this->assertFileExists(AVORG_BASE_PATH . "/view/$template");
		});
	}

	public function testReturnsTitleWhenNoEntity()
	{
		$this->assertPagesExist();

		array_walk($this->pages, function(Page $page) {
			$class = get_class($page);
			$this->assertEquals(
				"fake_title",
				$page->filterTitle("fake_title"),
				"$class page did not return provided title"
			);
		});
	}
}