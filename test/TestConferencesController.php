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
        $this->controller->handleGet(new WP_REST_Request());

        $this->mockAvorgApi->assertMethodCalled("getConferences");
    }

    public function testReturnsEntities()
    {
        $this->assertIsArray($this->controller->handleGet(new WP_REST_Request()));
    }

    public function testUsesStartParam()
    {
        $this->controller->handleGet(new WP_REST_Request(['start' => 25]));

        $this->mockAvorgApi->assertMethodCalledWith('getConferences', null, 25);
    }

    public function testUsesSearchParam()
    {
        $this->controller->handleGet(new WP_REST_Request(['search' => 'term']));

        $this->mockAvorgApi->assertMethodCalledWith('getConferences', 'term', null);
    }
}