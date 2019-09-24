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

    public function testRegistersArguments()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertAnyCallMatches("register_rest_route", function ($call) {
            $callArgs = $call[2]['args'] ?? null;

            $expectedArgs = [
                'search' => [
                    'description' => 'Search term',
                    'type' => 'string'
                ],
                'start' => [
                    'description' => 'Index of item in result set that should begin returned data',
                    'type' => 'integer'
                ]
            ];

            return $callArgs === $expectedArgs;
        });
    }

    public function testUsesStartParamToGetPresenters()
    {
        $this->controller->getData(['start' => 25]);

        $this->mockAvorgApi->assertMethodCalledWith('getPresenters', null, 25);
    }

    public function testUsesSearchParamToGetPresenters()
    {
        $this->controller->getData(['search' => 'term']);

        $this->mockAvorgApi->assertMethodCalledWith('getPresenters', 'term', null);
    }
}