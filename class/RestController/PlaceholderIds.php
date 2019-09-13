<?php

namespace Avorg\RestController;

use Avorg\RestController;
use function defined;

if (!defined('ABSPATH')) exit;

class PlaceholderIds extends RestController
{

    public function registerRoutes()
    {
        $this->wp->register_rest_route(
            'avorg/v1',
            '/placeholder-ids',
            [
                'methods' => 'GET',
                'callback' => [$this, 'getData']
            ]
        );
    }

    public function getData()
    {
        return $this->wp->get_all_meta_values("avorgBitIdentifier");
    }
}