<?php

namespace Avorg;

use WP_REST_Request;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

abstract class RestController
{
    protected $route;

    protected $getArgs = [];
    protected $postArgs = [];
    protected $putArgs = [];
    protected $deleteArgs = [];

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
                [
                    'methods' => 'GET',
                    'callback' => [$this, 'handleGet'],
                    'args' => $this->getArgs
                ],
                [
                    'methods' => 'POST',
                    'callback' => [$this, 'handlePost'],
                    'args' => $this->postArgs
                ],
                [
                    'methods' => 'PUT',
                    'callback' => [$this, 'handlePut'],
                    'args' => $this->putArgs
                ],
                [
                    'methods' => 'DELETE',
                    'callback' => [$this, 'handleDelete'],
                    'args' => $this->deleteArgs
                ],
            ]
        );
    }

    public function handleGet(WP_REST_Request $request)
    {
        return 'GET handler unimplemented';
    }

    public function handlePost(WP_REST_Request $request)
    {
        return 'POST handler unimplemented';
    }

    public function handlePut(WP_REST_Request $request)
    {
        return 'PUT handler unimplemented';
    }

    public function handleDelete(WP_REST_Request $request)
    {
        return 'DELETE handler unimplemented';
    }
}