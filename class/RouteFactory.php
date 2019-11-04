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
            return $this->buildPageRoute($pageId, $language->getBaseRoute());
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
        $routable = $isEndpointClass
            ? $this->endpointFactory->getEndpointByClass($class)
            : $this->pageFactory->getPage($class);
        $routeId = $routable->getRouteId();
        $firstFormat = $this->routeFormats[$class][0];

        return $isEndpointClass
            ? $this->buildEndpointRoute($routeId, $firstFormat)
            : $this->buildPageRoute($routeId, $firstFormat);
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

    /**
     * @param $routeId
     * @param $routeFormat
     * @return EndpointRoute
     * @throws ReflectionException
     */
    private function buildEndpointRoute($routeId, $routeFormat)
	{
	    return $this->factory
            ->make("Avorg\\Route\\EndpointRoute")
            ->setId($routeId)
            ->setFormat($routeFormat);
	}

    /**
     * @param $routeId
     * @param $routeFormat
     * @return PageRoute
     * @throws ReflectionException
     */
    private function buildPageRoute($routeId, $routeFormat)
    {
        return $this->factory
            ->make("Avorg\\Route\\PageRoute")
            ->setId($routeId)
            ->setFormat($routeFormat);
    }

    private function loadRouteFormats(): void
    {
        $this->routeFormats = array_reduce(file(AVORG_BASE_PATH . "/routes.csv"), function ($carry, $line) {
            $row = str_getcsv($line);
            $classname = $row[0];
            $format = $row[1];

            if (!array_key_exists($classname, $carry)) {
                $carry[$classname] = [];
            }

            $carry[$classname][] = $format;

            return $carry;
        }, []);
    }
}