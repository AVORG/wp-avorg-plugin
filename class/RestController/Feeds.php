<?php

namespace Avorg\RestController;

use Avorg\RestController;
use Avorg\RouteFactory;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Feeds extends RestController
{
    protected $route = '/feeds';

    /** @var RouteFactory $routeFactory */
    private $routeFactory;

    public function __construct(RouteFactory $routeFactory, WordPress $wp)
    {
        parent::__construct($wp);

        $this->routeFactory = $routeFactory;
    }

    public function handleGet($request = null)
    {
        $formats = $this->routeFactory->getEndpointRouteFormats();

        return array_filter(array_keys($formats), function($key) {
            return strstr($key, 'RssEndpoint') !== false;
        });
    }
}