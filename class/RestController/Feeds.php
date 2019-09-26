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

    public function getData($request = null)
    {
        $formats = $this->routeFactory->getEndpointRouteFormats();
        $filteredFormats = array_filter($formats, function($key) {
            return strstr($key, 'RssEndpoint') !== false;
        }, ARRAY_FILTER_USE_KEY);
        $feedKeys = array_map(function($key) {
            $pieces = explode("\\", $key);
            return end($pieces);
        }, array_keys($filteredFormats));

        return array_combine($feedKeys, array_values($filteredFormats));
    }
}