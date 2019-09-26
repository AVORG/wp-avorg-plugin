<?php

use Avorg\RestController\PlaceholderIds;

final class TestPlaceholderIdsController extends Avorg\TestCase
{
    /** @var PlaceholderIds $controller */
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\PlaceholderIds");
    }

    public function testExists()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertMethodCalledWith(
            'register_rest_route',
            'avorg/v1',
            '/placeholder-ids',
            [
                'methods' => 'GET',
                'callback' => [$this->controller, 'getData'],
                'args' => []
            ]
        );
    }

    public function testGetsIdentifiers()
    {
        $this->controller->getData();

        $this->mockWordPress->assertMethodCalledWith("get_all_meta_values", "avorgBitIdentifier");
    }

    public function testReturnsIdentifiers()
    {
        $this->mockWordPress->setReturnValue("get_all_meta_values", "values");

        $response = $this->controller->getData();

        $this->assertEquals("values", $response);
    }
}