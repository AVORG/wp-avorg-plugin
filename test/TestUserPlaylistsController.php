<?php

use Avorg\RestController\DataObjects\Series;

final class TestUserPlaylistsController extends Avorg\TestCase
{
    /** @var Series $controller */
    protected $controller;

    private $controllerName = "UserPlaylists";
    private $route = "/user/playlists";
    private $apiMethod = "getPlaylistsByUser";

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure(
            "Avorg\\RestController\\DataObjects\\{$this->controllerName}");

        $_SESSION = [
            'userId' => 'user_id',
            'sessionToken' => 'session_token'
        ];
    }

    public function testsRegistersRoute()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered($this->route);
    }

    public function testGetsEntities()
    {
        $this->controller->handleGet(new WP_REST_Request());

        $this->mockAvorgApi->assertMethodCalled($this->apiMethod);
    }

    public function testUsesStartParam()
    {
        $this->controller->handleGet(new WP_REST_Request(['start' => 25]));

        $this->mockAvorgApi->assertMethodCalledWith($this->apiMethod, 'user_id', 'session_token', null, 25);
    }

    public function testUsesSearchParam()
    {
        $this->controller->handleGet(new WP_REST_Request(['search' => 'term']));

        $this->mockAvorgApi->assertMethodCalledWith($this->apiMethod, 'user_id', 'session_token', 'term', null);
    }
}