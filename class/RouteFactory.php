<?php

namespace Avorg;

use Avorg\Route\EndpointRoute;
use Avorg\Route\PageRoute;
use function defined;
use natlib\Factory;

if (!defined('ABSPATH')) exit;


class RouteFactory
{
	/** @var EndpointFactory $endpointFactory */
	private $endpointFactory;

	/** @var Factory $factory */
	private $factory;

	/** @var LanguageFactory $languageFactory */
	private $languageFactory;

	/** @var PageFactory $pageFactory */
	private $pageFactory;

	/** @var WordPress $wp */
	private $wp;

	private $pageRouteFormats = [
		"Avorg\Page\Presenter\Listing" => "{ language }/sermons/presenters[/{ letter }]",
		"Avorg\Page\Presenter\Detail" => "{ language }/sermons/presenters/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Media" => "{ language }/sermons/recordings/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Book\Listing" => "{ language }/audiobooks/books",
		"Avorg\Page\Book\Detail" => "{ language }/audiobooks/books/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Playlist\Listing" => "{ language }/playlists/lists",
		"Avorg\Page\Playlist\Detail" => "{ language }/playlists/lists/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Topic\Listing" => "{ language }/topics",
		"Avorg\Page\Topic\Detail" => "{ language }/topics/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Bible\Listing" => "{ language }/audiobibles/volumes",
		"Avorg\Page\Bible\Detail" => "{ language }/audiobibles/books/{ version }/{ drama }",
		"Avorg\Page\Story\Listing" => "{ language }/audiobooks/stories",
		"Avorg\Page\Story\Detail" => "{ language }/audiobooks/stories/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Conference\Listing" => "{ language }/sermons/conferences",
		"Avorg\Page\Conference\Detail" => "{ language }/sermons/conferences/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Sponsor\Listing" => "{ language }/sponsors",
		"Avorg\Page\Sponsor\Detail" => "{ language }/sponsors/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Series\Listing" => "{ language }/sermons/series",
		"Avorg\Page\Series\Detail" => "{ language }/sermons/series/{ entity_id:[0-9]+ }[/{ slug }]",
	];

	private $endpointRouteFormats = [
		"Avorg\Endpoint\RssEndpoint\RssLatest" => "{ language }/podcasts/latest",
		"Avorg\Endpoint\RssEndpoint\RssSpeaker" => "{ language }/sermons/presenters/podcast/{ entity_id:[0-9]+ }/latest/{ slug }",
		"Avorg\Endpoint\Recording" => "api/presentation/{ entity_id:[0-9]+ }",
		"Avorg\Endpoint\RssEndpoint\RssTrending" => "{ language }/podcasts/trending",
		"Avorg\Endpoint\RssEndpoint\RssTopic" => "{ language }/topics/podcast/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Endpoint\RssEndpoint\RssSponsor" => "{ language }/sponsors/podcast/{ entity_id:[0-9]+ }/latest[/{ slug }]"
	];

	public function __construct(
		EndpointFactory $endpointFactory,
		Factory $factory,
		LanguageFactory $languageFactory,
		PageFactory $pageFactory,
		WordPress $wp
	)
	{
		$this->endpointFactory = $endpointFactory;
		$this->factory = $factory;
		$this->languageFactory = $languageFactory;
		$this->pageFactory = $pageFactory;
		$this->wp = $wp;
	}

	public function getPageRouteByClass($class)
	{
		/** @var Page $page */
		$page = $this->pageFactory->getPage($class);
		$routeId = $page->getRouteId();
		$format = $this->pageRouteFormats[$class];

		return $this->getPageRoute($routeId, $format);
	}

	public function getRoutes()
	{
		return array_merge(
			$this->getPageRoutes(),
			$this->getEndpointRoutes(),
			$this->getLanguageRoutes()
		);
	}

	/**
	 * @return array
	 */
	private function getPageRoutes()
	{
		$classFormatPairs = $this->pageRouteFormats;
		$objectFactoryMethod = [$this->pageFactory, "getPage"];
		$routeFactoryMethod = [$this, "getPageRoute"];

		return $this->buildRoutes($classFormatPairs, $objectFactoryMethod, $routeFactoryMethod);
	}

	private function getEndpointRoutes()
	{
		$classFormatPairs = $this->endpointRouteFormats;
		$objectFactoryMethod = [$this->endpointFactory, "getEndpointByClass"];
		$routeFactoryMethod = [$this, "getEndpointRoute"];

		return $this->buildRoutes($classFormatPairs, $objectFactoryMethod, $routeFactoryMethod);
	}

	private function getLanguageRoutes()
	{
		$pageId = $this->wp->get_option("page_on_front");
		$languages = $this->languageFactory->getLanguages();

		return array_map(function (Language $language) use ($pageId) {
			return $this->getPageRoute($pageId, $language->getBaseRoute());
		}, $languages);
	}

	/**
	 * @param array $classFormatPairs
	 * @param array $objectFactoryMethod
	 * @param array $routeFactoryMethod
	 * @return array
	 */
	private function buildRoutes(array $classFormatPairs, array $objectFactoryMethod, array $routeFactoryMethod)
	{
		$classes = array_keys($classFormatPairs);
		$objects = array_map($objectFactoryMethod, $classes);
		$routeIds = array_map(function (iRoutable $object) {
			return $object->getRouteId();
		}, $objects);
		$formats = array_values($classFormatPairs);

		return array_map($routeFactoryMethod, $routeIds, $formats);
	}

	private function getPageRoute($routeId, $routeFormat)
	{
		/** @var PageRoute $route */
		$route = $this->factory->obtain("Avorg\\Route\\PageRoute");

		return $route->setId($routeId)->setFormat($routeFormat);
	}

	/** @noinspection PhpUnusedPrivateMethodInspection */
	private function getEndpointRoute($routeId, $routeFormat)
	{
		/** @var EndpointRoute $route */
		$route = $this->factory->obtain("Avorg\\Route\\EndpointRoute");

		return $route->setId($routeId)->setFormat($routeFormat);
	}
}