<?php

use Avorg\RestController\PlaceholderContent;

final class TestPlaceholderContentController extends Avorg\TestCase
{
    /** @var PlaceholderContent $controller */
    protected $controller;

    public function setUp()
    {
        parent::setUp();

        $this->controller = $this->factory->secure("Avorg\\RestController\\PlaceholderContent");
    }

    public function testRegistersCallbacks()
    {
        $this->controller->registerCallbacks();

        $this->mockWordPress->assertActionAdded('rest_api_init', [$this->controller, 'registerRoutes']);
    }

    public function testRegisterRoutes()
    {
        $this->controller->registerRoutes();

        $this->mockWordPress->assertMethodCalledWith(
            'register_rest_route',
            'avorg/v1',
            '/placeholder-content/(?P<id>[\d]+)',
            [
                'methods' => 'GET',
                'callback' => [$this->controller, 'getItem'],
                'args' => [
                    'media_id' => null
                ]
            ]
        );
    }

    public function testGetItem()
    {
        $this->controller->getItem([
            'id' => 'identifier',
            'media_id' => 'media_id'
        ]);

        $this->mockWordPress->assertMethodCalledWith('get_posts', [
            'posts_per_page' => -1,
            'post_type' => 'avorg-content-bits',
            'meta_query' => [
                [
                    'key' => 'avorgBitIdentifier',
                    'value' => 'identifier'
                ]
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'avorgMediaIds',
                    'field' => 'slug',
                    'terms' => 'media_id'
                ]
            ]
        ]);
    }

    public function testGetItemGetsUnassociatedItemsIfNoAssociatedItems()
    {
        $this->controller->getItem([
            'id' => 'identifier',
            'media_id' => 'media_id'
        ]);

        $this->mockWordPress->assertMethodCalledWith('get_posts', [
            'posts_per_page' => -1,
            'post_type' => 'avorg-content-bits',
            'meta_query' => [
                [
                    'key' => 'avorgBitIdentifier',
                    'value' => 'identifier'
                ]
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'avorgMediaIds',
                    'field' => 'slug',
                    'terms' => null
                ]
            ]
        ]);
    }

    public function testSelectsItemRandomly()
    {
        $this->mockWordPress->setReturnValue('get_posts', ['item_1', 'item_2']);

        $this->controller->getItem([
            'id' => 'identifier',
            'media_id' => 'media_id'
        ]);

        $this->mockPhp->assertMethodCalledWith('array_rand', ['item_1', 'item_2']);
    }

    public function testReturnsPostContent()
    {
        $post = $this->arrayToObject([
            'post_content' => 'the_content'
        ]);

        $this->mockWordPress->setReturnValue('get_posts', [$post]);
        $this->mockPhp->setReturnValue('array_rand', 0);

        $content = $this->controller->getItem([
            'id' => 'identifier',
            'media_id' => 'media_id'
        ]);

        $this->assertEquals('the_content', $content);
    }

    public function testDoesNotAttemptSelectionWhenNoPosts()
    {
        $this->mockWordPress->setReturnValue('get_posts', []);

        $this->controller->getItem([
            'id' => 'identifier',
            'media_id' => 'media_id'
        ]);

        $this->mockPhp->assertMethodNotCalled('array_rand');
    }
}