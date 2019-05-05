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
}