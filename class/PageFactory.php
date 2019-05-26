<?php

namespace Avorg;

use natlib\Factory;
use natlib\Stub;

if (!\defined('ABSPATH')) exit;

class PageFactory
{
	/** @var Factory $factory */
	private $factory;

	/** @var Filesystem $filesystem */
	private $filesystem;

	public function __construct(Factory $factory, Filesystem $filesystem)
	{
		$this->factory = $factory;
		$this->filesystem = $filesystem;
	}

	/**
	 * @return array
	 */
	public function getPages()
	{
		$classes = [
			"Avorg\\Page\\Playlist",
			"Avorg\\Page\\Presenter\\Detail",
			"Avorg\\Page\\Presenter\\Listing"
		];

		$objects = array_map(function ($class) {
			return $this->factory->secure($class);
		}, $classes);

		$pages = array_merge($objects, [
			$this->getMediaPage(),
			$this->getTopicPage()
		]);

		return $pages;
	}

	public function getTopicPage()
	{
		/** @var Page $page */
		$page = $this->factory->make("Avorg\\Page");
		$dataProvider = $this->factory->secure("Avorg\\TopicDataProvider");

		$page->setPageIdentifier("avorg_page_id_avorg_page_topic");
		$page->setDefaultTitle("Topic Detail");
		$page->setDefaultContent("Topic Detail");
		$page->setTwigTemplate("organism-topic.twig");
		$page->setRouteFormat("{ language }/topics/{ entity_id:[0-9]+ }[/{ slug }]");
		$page->setDataProvider($dataProvider);

		return $page;
	}

	public function getMediaPage()
	{
		/** @var Page $page */
		$page = $this->factory->make("Avorg\\Page");
		$dataProvider = $this->factory->secure("Avorg\\PresentationDataProvider");

		$page->setPageIdentifier("avorg_page_id_avorg_page_media");
		$page->setDefaultTitle("Media Detail");
		$page->setDefaultContent("Media Detail");
		$page->setTwigTemplate("organism-recording.twig");
		$page->setRouteFormat("{ language }/sermons/recordings/{ entity_id:[0-9]+ }[/{ slug }]");
		$page->setDataProvider($dataProvider);
		$page->setTitleProvider($dataProvider);

		return $page;
	}
}