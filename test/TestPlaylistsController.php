<?php

use Avorg\RestController\DataObjects\Playlists;

final class TestPlaylistsController extends Avorg\TestCase
{
    /** @var Playlists $controller */
    protected $controller;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\DataObjects\\Playlists");
    }

    public function testsRegistersRoute()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertRestRouteRegistered("/playlists");
    }

    public function testGetsEntities()
    {
        $this->controller->getData();

        $this->mockAvorgApi->assertMethodCalled("getPlaylists");
    }

    public function testUsesStartParam()
    {
        $this->controller->getData(['start' => 25]);

        $this->mockAvorgApi->assertMethodCalledWith('getPlaylists', null, 25);
    }

    public function testUsesSearchParam()
    {
        $this->controller->getData(['search' => 'term']);

        $this->mockAvorgApi->assertMethodCalledWith('getPlaylists', 'term', null);
    }
}