<?php

use Avorg\RouteFactory;

final class TestRouteFactory extends Avorg\TestCase
{
	/** @var RouteFactory $routeFactory */
	protected $routeFactory;

	public function setUp(): void
	{
		parent::setUp();

		$this->routeFactory = $this->factory->secure("Avorg\\RouteFactory");
	}

	public function testGetRouteByClassReturnsPageRoute()
	{
		$route = $this->routeFactory->getRouteByClass("Avorg\Page\Presenter\Listing");

		$this->assertInstanceOf("Avorg\\Route\\PageRoute", $route);
	}

	public function testGetEndpointRouteFormats()
    {
        $routes = $this->routeFactory->getEndpointRouteFormats();
        $misfits = array_filter(array_keys($routes), function($format) {
            return strstr($format, '\\Endpoint\\') === False;
        });

        $this->assertEmpty($misfits);
    }

    public function testGetRouteByClassReturnsEndpointRoutes()
    {
        $result = $this->routeFactory->getRouteByClass('Avorg\Endpoint\RssEndpoint\Latest');

        $this->assertInstanceOf("Avorg\\Route\\EndpointRoute", $result);
    }

    public function testEndpointRoutesCount()
    {
        $endpointRouteFormatsCount = count($this->routeFactory->getEndpointRouteFormats());
        $routes = $this->routeFactory->getRoutes();
        $endpointRoutes = array_filter($routes, function($route) {
            return is_a($route, "Avorg\\Route\\EndpointRoute");
        });

        $this->assertEquals($endpointRouteFormatsCount, count($endpointRoutes));
    }
}