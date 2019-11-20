<?php

use Avorg\RestController\PlaceholderContent;

final class TestPlaceholderContentController extends Avorg\TestCase
{
    /** @var PlaceholderContent $controller */
    protected $controller;

    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\PlaceholderContent");
    }

    public function testExists()
    {
        $this->controller->registerRoutes();

        /* TODO: Change to /placeholder-content/7; extract media ids on front-end */
        $this->mockWordPress->assertRestRouteRegistered('/placeholder-content/(?P<id>\d+)');
    }

    public function testGetsMediaIds()
    {
        $this->controller->handleGet(new WP_REST_Request([
            'id' => 7
        ]));

        $this->mockWordPress->assertMethodCalledWith(
            "get_post_meta",
            7,
            "avorgMediaIds",
            true
        );
    }

    public function testReturnsData()
    {
        $this->mockWordPress->setReturnValue("get_post_meta", "values");

        $response = $this->controller->handleGet(new WP_REST_Request([
            'id' => 7
        ]));

        $this->assertEquals([
            'id' => 7,
            'media_ids' => "values"
        ], $response);
    }

    public function testCoercesIntegerId()
    {
        $response = $this->controller->handleGet(new WP_REST_Request([
            'id' => '7'
        ]));

        $this->assertTrue($response['id'] === 7);
    }
}