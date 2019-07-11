<?php

use Avorg\RouteFactory;

final class TestRouteFactory extends Avorg\TestCase
{
	/** @var RouteFactory $routeFactory */
	protected $routeFactory;

	public function setUp()
	{
		parent::setUp();

		$this->routeFactory = $this->factory->secure("Avorg\\RouteFactory");
	}

	public function testGetRouteByClassReturnsPageRoute()
	{
		$route = $this->routeFactory->getRouteByClass("Avorg\Page\Presenter\Listing");

		$this->assertInstanceOf("Avorg\\Route\\PageRoute", $route);
	}
}