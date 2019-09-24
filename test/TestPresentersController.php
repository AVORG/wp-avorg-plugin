<?php

use Avorg\RestController\PlaceholderIds;

final class TestPresentersController extends Avorg\TestCase
{
    /** @var PlaceholderIds $controller */
    protected $controller;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\Presenters");
    }

    public function testExists()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered("/presenters");
    }

    public function testGetsPresenters()
    {
        $this->controller->getData();

        $this->mockAvorgApi->assertMethodCalled("getPresenters");
    }

    public function testReturnsPresenters()
    {
        $this->assertIsArray($this->controller->getData());
    }
}