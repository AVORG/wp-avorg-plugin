<?php

namespace Avorg;

use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

abstract class RestController
{
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

    public abstract function registerRoutes();
}