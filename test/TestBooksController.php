<?php

use Avorg\RestController\DataObjects\Books;

final class TestBooksController extends Avorg\TestCase
{
    /** @var Books $controller */
    protected $controller;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\DataObjects\\Books");
    }

    public function testsRegistersRoute()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered("/books");
    }

    public function testGetsEntities()
    {
        $this->controller->getData();

        $this->mockAvorgApi->assertMethodCalled("getBooks");
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

        $this->mockAvorgApi->assertMethodCalledWith('getBooks', null, 25);
    }

    public function testUsesSearchParam()
    {
        $this->controller->getData(['search' => 'term']);

        $this->mockAvorgApi->assertMethodCalledWith('getBooks', 'term', null);
    }
}