<?php

namespace Avorg;

use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

abstract class RestController
{
    protected $route;

    /** @var WordPress $wp */
    protected $wp;

    public function __construct(WordPress $wp)
    {
        $this->wp = $wp;
    }

    public function registerCallbacks()
    {
        $this->wp->add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes()
    {
        $this->wp->register_rest_route(
            'avorg/v1',
            $this->route,
            [
                'methods' => 'GET',
                'callback' => [$this, 'getData']
            ]
        );
    }

    abstract public function getData();
}