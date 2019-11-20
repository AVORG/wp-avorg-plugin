<?php

namespace Avorg\RestController;

use Avorg\RestController;
use WP_REST_Request;
use function defined;

if (!defined('ABSPATH')) exit;

class PlaceholderContent extends RestController
{
    protected $route = '/placeholder-content/(?P<id>\d+)';

    public function handleGet(WP_REST_Request $request)
    {
        $post_id = (int) $request['id'];
        $media_ids = $this->wp->get_post_meta($post_id, 'avorgMediaIds', true);

        return [
            'id' => $post_id,
            'media_ids' => $media_ids
        ];
    }
}