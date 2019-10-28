<?php

use Avorg\RestController;

class BareController extends RestController
{
}

final class TestRestController extends Avorg\TestCase
{
    /** @var RestController $controller */
    private $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->make('BareController');
    }

    public function testRegistersRoutes()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertMethodCalledWith(
            'register_rest_route',
            'avorg/v1',
            null,
            [
                [
                    'methods' => 'GET',
                    'callback' => [$this->controller, 'handleGet'],
                    'args' => []
                ],
                [
                    'methods' => 'POST',
                    'callback' => [$this->controller, 'handlePost'],
                    'args' => []
                ],
                [
                    'methods' => 'PUT',
                    'callback' => [$this->controller, 'handlePut'],
                    'args' => []
                ],
                [
                    'methods' => 'DELETE',
                    'callback' => [$this->controller, 'handleDelete'],
                    'args' => []
                ],
            ]
        );
    }

    /**
     * @dataProvider httpMethodProvider
     * @param $httpMethod
     */
    public function testHandleGetMethod($httpMethod)
    {
        $phpMethod = 'handle' . ucfirst(strtolower($httpMethod));
        $response = $this->controller->$phpMethod(new WP_REST_Request());

        $this->assertEquals("$httpMethod handler unimplemented", $response);
    }

    public function httpMethodProvider()
    {
        return [
            ['GET'],
            ['POST'],
            ['PUT'],
            ['DELETE']
        ];
    }
}