<?php

namespace Avorg;

use Avorg\Route\EndpointRoute;
use Avorg\Route\PageRoute;
use natlib\Factory;

if (!\defined('ABSPATH')) exit;


class RouteFactory
{
	/** @var EndpointFactory $endpointFactory */
	private $endpointFactory;

	/** @var Factory $factory */
	private $factory;

	/** @var PageFactory $pageFactory */
	private $pageFactory;

	private $pageRouteFormats = [
		"Avorg\Page\Presenter\Listing" => "{ language }/sermons/presenters[/{ letter }]",
		"Avorg\Page\Presenter\Detail" => "{ language }/sermons/presenters/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Media" => "{ language }/sermons/recordings/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Playlist" => "{ language }/playlists/lists/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Topic" => "{ language }/topics/{ entity_id:[0-9]+ }[/{ slug }]",
		"Avorg\Page\Book\Listing" => "{ language }/audiobooks/books",
		"Avorg\Page\Playlist\Listing" => "{ language }/playlists/lists"
	];

	private $endpointRouteFormats = [
		"Avorg\Endpoint\RssEndpoint\RssLatest" => "{ language }/podcasts/latest",
		"Avorg\Endpoint\RssEndpoint\RssSpeaker" => "{ language }/sermons/presenters/podcast/{ entity_id:[0-9]+ }/latest/{ slug }",
		"Avorg\Endpoint\PresentationEndpoint" => "api/presentation/{ entity_id:[0-9]+ }"
	];

	public function __construct(
		EndpointFactory $endpointFactory,
		Factory $factory,
		PageFactory $pageFactory
	)
	{
		$this->endpointFactory = $endpointFactory;
		$this->factory = $factory;
		$this->pageFactory = $pageFactory;
	}

	public function getRoutes()
	{
		return array_merge(
			$this->getPageRoutes(),
			$this->getEndpointRoutes()
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

	public function getPageRoute($routeId, $routeFormat)
	{
		/** @var PageRoute $route */
		$route = $this->factory->obtain("Avorg\\Route\\PageRoute");

		return $route->setId($routeId)->setFormat($routeFormat);
	}

	public function getEndpointRoute($routeId, $routeFormat)
	{
		/** @var EndpointRoute $route */
		$route = $this->factory->obtain("Avorg\\Route\\EndpointRoute");

		return $route->setId($routeId)->setFormat($routeFormat);
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
}