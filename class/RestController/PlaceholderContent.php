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
            '/placeholder-content/(?P<id>[\w]+)(?:/(?P<media_id>[\d]+))?',
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
        return $this->getPosts($data['id'], $data['media_id']) ?: $this->getPosts($data['id']);
    }

    /**
     * @param $identifier
     * @param null $mediaId
     */
    private function getPosts($identifier, $mediaId = null)
    {
        $args = array_merge(
            [
                'posts_per_page' => -1,
                'post_type' => 'avorg-content-bits',
                'meta_query' => [
                    [
                        'key' => 'avorgBitIdentifier',
                        'value' => $identifier
                    ]
                ]
            ],
            ($mediaId) ? [
                'tax_query' => [
                    [
                        'taxonomy' => 'avorgMediaIds',
                        'field' => 'slug',
                        'terms' => $mediaId
                    ]
                ]
            ] : []
        );

        return $this->wp->get_posts($args);
    }
}