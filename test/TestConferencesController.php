<?php

use Avorg\RestController\DataObjects\Conferences;

final class TestConferencesController extends Avorg\TestCase
{
    /** @var Conferences $controller */
    protected $controller;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\DataObjects\\Conferences");
    }

    public function testsRegistersRoute()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered("/conferences");
    }

    public function testGetsEntities()
    {
        $this->controller->handleGet();

        $this->mockAvorgApi->assertMethodCalled("getConferences");
    }

    public function testReturnsEntities()
    {
        $this->assertIsArray($this->controller->handleGet());
    }

    public function testUsesStartParam()
    {
        $this->controller->handleGet(['start' => 25]);

        $this->mockAvorgApi->assertMethodCalledWith('getConferences', null, 25);
    }

    public function testUsesSearchParam()
    {
        $this->controller->handleGet(['search' => 'term']);

        $this->mockAvorgApi->assertMethodCalledWith('getConferences', 'term', null);
    }
}