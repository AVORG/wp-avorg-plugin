<?php

namespace Avorg\RestController;

use Avorg\Php;
use Avorg\RestController;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class PlaceholderContent extends RestController
{
    /** @var Php $php */
    private $php;

    public function __construct(Php $php, WordPress $wp)
    {
        parent::__construct($wp);

        $this->php = $php;
    }

    public function registerRoutes()
    {
        $this->wp->register_rest_route(
            'avorg/v1',
            '/placeholder-content/(?P<id>[\d]+)',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getItem'],
                'args' => [
                    'media_id' => null
                ]
            ]
        );
    }

    public function getItem($data)
    {
        $posts = $this->getPosts($data['id'], $data['media_id']) ?: $this->getPosts($data['id']);

        if (!$posts) return null;

        $post = (object)$this->randomChoice($posts);

        return property_exists($post, 'post_content') ? $post->post_content : null;
    }

    /**
     * @param $identifier
     * @param null $mediaId
     */
    private function getPosts($identifier, $mediaId = null)
    {
        return $this->wp->get_posts([
            'posts_per_page' => -1,
            'post_type' => 'avorg-content-bits',
            'meta_query' => [
                [
                    'key' => 'avorgBitIdentifier',
                    'value' => $identifier
                ]
            ],
            'tax_query' => [
                [
                    'taxonomy' => 'avorgMediaIds',
                    'field' => 'slug',
                    'terms' => $mediaId
                ]
            ]
        ]);
    }

    /**
     * @param $items
     * @return mixed
     */
    private function randomChoice($items)
    {
        $i = $this->php->array_rand($items);

        return array_key_exists($i, $items) ? $items[$i] : null;
    }
}