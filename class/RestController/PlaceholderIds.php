<?php

namespace Avorg\RestController;

use Avorg\RestController;
use WP_REST_Request;
use function defined;

if (!defined('ABSPATH')) exit;

class PlaceholderIds extends RestController
{
    protected $route = '/placeholder-ids';

    public function handleGet(WP_REST_Request $request)
    {
        return $this->wp->get_all_meta_values("avorgBitIdentifier");
    }
}