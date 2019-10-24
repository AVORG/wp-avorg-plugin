<?php

use Avorg\RestController\Favorites;

final class TestFavoritesController extends Avorg\TestCase
{
    /** @var Favorites $controller */
    protected $controller;

    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\Favorites");
    }

    public function testAddFavorite()
    {
        $_SESSION['userId'] = 'user_id';
        $_SESSION['sessionToken'] = 'session_token';

        $this->controller->getData([
            'method' => 'POST',
            'presentationId' => 'entity_id'
        ]);

        $this->mockAvorgApi->assertMethodCalledWith("addFavorite", 'entity_id', 'user_id',
            'session_token');
    }

    public function testDeleteFavorite()
    {
        $_SESSION['userId'] = 'user_id';
        $_SESSION['sessionToken'] = 'session_token';

        $this->controller->getData([
            'method' => 'DELETE',
            'presentationId' => 'entity_id'
        ]);

        $this->mockAvorgApi->assertMethodCalledWith("addFavorite", 'entity_id', 'user_id',
            'session_token');
    }
}