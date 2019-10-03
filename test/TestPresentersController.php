<?php

use Avorg\RestController\DataObjects\Presenters;

final class TestPresentersController extends Avorg\TestCase
{
    /** @var Presenters $controller */
    protected $controller;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\DataObjects\\Presenters");
    }

    public function testExists()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered("/presenters");
    }

    public function testGetsEntities()
    {
        $this->controller->getData();

        $this->mockAvorgApi->assertMethodCalled("getPresenters");
    }

    public function testReturnsEntities()
    {
        $this->assertIsArray($this->controller->getData());
    }

    public function testRegistersArguments()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteQueryVarsRegistered([
            'search' => [
                'description' => 'Search term',
                'type' => 'string'
            ],
            'start' => [
                'description' => 'Index of item in result set that should begin returned data',
                'type' => 'integer'
            ]
        ]);
    }

    public function testUsesStartParam()
    {
        $this->controller->getData(['start' => 25]);

        $this->mockAvorgApi->assertMethodCalledWith('getPresenters', null, 25);
    }

    public function testUsesSearchParam()
    {
        $this->controller->getData(['search' => 'term']);

        $this->mockAvorgApi->assertMethodCalledWith('getPresenters', 'term', null);
    }
}