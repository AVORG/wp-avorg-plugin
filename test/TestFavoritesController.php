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

        $_SESSION['userId'] = 'user_id';
        $_SESSION['sessionToken'] = 'session_token';

        $this->controller = $this->factory->secure("Avorg\\RestController\\Favorites");
    }

    public function testAddFavorite()
    {
        $this->controller->handlePost([
            'presentationId' => 'entity_id'
        ]);

        $this->mockAvorgApi->assertMethodCalledWith(
            "addFavorite",
            'entity_id',
            'user_id',
            'session_token'
        );
    }

    public function testDeleteFavorite()
    {
        $this->controller->handleDelete([
            'presentationId' => 'entity_id'
        ]);

        $this->mockAvorgApi->assertMethodCalledWith(
            "unFavorite",
            'entity_id',
            'user_id',
            'session_token'
        );
    }

    public function testGetGetsFavorites()
    {
        $this->controller->handleGet();

        $this->mockAvorgApi->assertMethodCalledWith(
            'getFavorites',
            'user_id',
            'session_token'
        );
    }

    public function testReturnsFavorites()
    {
        $this->mockAvorgApi->setReturnValue('getFavorites', 'the_favorites');

        $response = $this->controller->handleGet();

        $this->assertEquals('the_favorites', $response);
    }

    public function testIsFavoritedCheck()
    {
        $this->controller->handleGet(['presentationId' => 'the_id']);

        $this->mockAvorgApi->assertMethodCalledWith(
            'isFavorited',
            'the_id',
            'user_id',
            'session_token'
        );
    }

    public function testReturnsIsFavoritedResponse()
    {
        $this->mockAvorgApi->setReturnValue('isFavorited', True);

        $response = $this->controller->handleGet(['presentationId' => 'the_id']);

        $this->assertTrue($response);
    }
}