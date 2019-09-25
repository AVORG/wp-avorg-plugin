<?php

use Avorg\RestController\DataObjects\Series;

final class TestSeriesController extends Avorg\TestCase
{
    /** @var Series $controller */
    protected $controller;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\DataObjects\\Series");
    }

    public function testsRegistersRoute()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered("/series");
    }

    public function testGetsEntities()
    {
        $this->controller->getData();

        $this->mockAvorgApi->assertMethodCalled("getAllSeries");
    }

    public function testUsesStartParam()
    {
        $this->controller->getData(['start' => 25]);

        $this->mockAvorgApi->assertMethodCalledWith('getAllSeries', null, 25);
    }

    public function testUsesSearchParam()
    {
        $this->controller->getData(['search' => 'term']);

        $this->mockAvorgApi->assertMethodCalledWith('getAllSeries', 'term', null);
    }
}