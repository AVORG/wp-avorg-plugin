<?php

use Avorg\RestController\Feeds;

final class TestFeedsController extends Avorg\TestCase
{
    /** @var Feeds $controller */
    protected $controller;

    private $controllerName = "Feeds";
    private $route = "/feeds";

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure(
            "Avorg\\RestController\\{$this->controllerName}");
    }

    public function testsRegistersRoute()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered($this->route);
    }

    public function testReturnsRoutes()
    {
        $data = $this->controller->getData();

        $this->assertArrayHasKey('Speaker', $data);
    }

    public function testDoesNotReturnNonFeedRoutes()
    {
        $data = $this->controller->getData();

        $this->assertArrayNotHasKey('Presentations', $data);
    }
}