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

	public function testHasDefaultTitleAndContent()
	{
		$this->mockWordPress->setReturnValue("get_option", 7);
		$this->mockWordPress->setReturnValue("get_post_status", false);

		$pages = $this->pageFactory->getPages();

		array_walk($pages, function(\Avorg\Page $page) {
			$page->createPage();
		});

		$this->mockWordPress->assertNoCallsMatch("wp_insert_post", function($call) {
			$postArray = $call[0];
			return (! $postArray["post_content"]) || (! $postArray["post_title"]);
		});
	}
}