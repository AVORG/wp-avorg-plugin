<?php

use Avorg\RestController\Feeds;

final class TestSuggestionsController extends Avorg\TestCase
{
    /** @var Feeds $controller */
    protected $controller;

    private $controllerName = "Suggestions";
    private $route = "/suggestions";

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

    public function testMakesSearch()
    {
        $this->mockDatabase->setReturnValue('searchWeights', []);

        $this->controller->handleGet(new WP_REST_Request([
            "term" => "the_term"
        ]));

        $this->mockDatabase->assertMethodCalledWith('searchWeights', 'the_term');
    }

    public function testReturnsTopFive()
    {
        $this->mockDatabase->setReturnValue('searchWeights', [
            ['type' => 'another_type', 'relevance' => 1, 'title' => 'a'],
            ['type' => 'the_type', 'relevance' => 1, 'title' => 'a'],
            ['type' => 'the_type', 'relevance' => 2, 'title' => 'b'],
            ['type' => 'the_type', 'relevance' => 3, 'title' => 'c'],
            ['type' => 'the_type', 'relevance' => 4, 'title' => 'd'],
            ['type' => 'the_type', 'relevance' => 5, 'title' => 'e'],
            ['type' => 'the_type', 'relevance' => 6, 'title' => 'f'],
        ]);

        $result = $this->controller->handleGet(new WP_REST_Request([
            "term" => "the_term"
        ]));

        $this->assertEquals([
            ['type' => 'the_type', 'relevance' => 6, 'title' => 'f'],
            ['type' => 'the_type', 'relevance' => 5, 'title' => 'e'],
            ['type' => 'the_type', 'relevance' => 4, 'title' => 'd'],
            ['type' => 'the_type', 'relevance' => 3, 'title' => 'c'],
            ['type' => 'the_type', 'relevance' => 2, 'title' => 'b'],
            ['type' => 'another_type', 'relevance' => 1, 'title' => 'a'],
        ], $result);
    }
}