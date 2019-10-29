<?php

use Avorg\RestController\DataObjects\Topics;

final class TestTopicsController extends Avorg\TestCase
{
    /** @var Topics $controller */
    protected $controller;

    private $controllerName = "Topics";
    private $route = "/topics";
    private $apiMethod = "getTopics";

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure(
            "Avorg\\RestController\\DataObjects\\{$this->controllerName}");
    }

    public function testsRegistersRoute()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered($this->route);
    }

    public function testGetsEntities()
    {
        $this->controller->handleGet();

        $this->mockAvorgApi->assertMethodCalled($this->apiMethod);
    }

    public function testUsesStartParam()
    {
        $this->controller->handleGet(['start' => 25]);

        $this->mockAvorgApi->assertMethodCalledWith($this->apiMethod, null, 25);
    }

    public function testUsesSearchParam()
    {
        $this->controller->handleGet(['search' => 'term']);

        $this->mockAvorgApi->assertMethodCalledWith($this->apiMethod, 'term', null);
    }
}