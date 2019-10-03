<?php

namespace Avorg;

use Avorg\Route\EndpointRoute;
use Avorg\Route\PageRoute;
use ReflectionException;
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

	private $routeFormats;

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

        $this->loadRouteFormats();
    }

    public function getRoutes()
    {
        return array_merge(
            $this->getLanguageRoutes(),
            $this->getStandardRoutes()
        );
    }

    private function getLanguageRoutes()
    {
        $pageId = $this->wp->get_option("page_on_front");
        $languages = $this->languageFactory->getLanguages();

        return array_map(function (Language $language) use ($pageId) {
            return $this->getPageRoute($pageId, $language->getBaseRoute());
        }, $languages);
    }

    private function getStandardRoutes()
    {
        return array_map(function($class) {
            return $this->getRouteByClass($class);
        }, array_keys($this->routeFormats));
    }

    /**
     * @param $class
     * @return Route|null
     * @throws ReflectionException
     */
    public function getRouteByClass($class)
    {
        if (!array_key_exists($class, $this->routeFormats)) return null;

        $isEndpointClass = strstr($class, "\\Endpoint\\") !== False;
        $endpoint = $isEndpointClass
            ? $this->endpointFactory->getEndpointByClass($class)
            : $this->pageFactory->getPage($class);
        $routeId = $endpoint->getRouteId();

        return $isEndpointClass ? $this->getEndpointRoute($routeId, $this->routeFormats[$class])
            : $this->getPageRoute($routeId, $this->routeFormats[$class]);
    }

    /**
     * @return array
     */
    public function getEndpointRouteFormats(): array
    {
        $endpointKeys = array_filter(array_keys($this->routeFormats), function($key) {
            return strstr($key, '\\Endpoint\\') !== False;
        });

        return array_intersect_key($this->routeFormats, array_flip($endpointKeys));
    }

	private function getEndpointRoute($routeId, $routeFormat)
	{
		/** @var EndpointRoute $route */
		$route = $this->factory->make("Avorg\\Route\\EndpointRoute");

		return $route->setId($routeId)->setFormat($routeFormat);
	}

    private function getPageRoute($routeId, $routeFormat)
    {
        /** @var PageRoute $route */
        $route = $this->factory->make("Avorg\\Route\\PageRoute");

        return $route->setId($routeId)->setFormat($routeFormat);
    }

    public function loadRouteFormats(): void
    {
        $this->routeFormats = array_reduce(file(AVORG_BASE_PATH . "/routes.csv"), function ($carry, $line) {
            $row = str_getcsv($line);
            return array_merge($carry, [$row[0] => $row[1]]);
        }, []);
    }
}