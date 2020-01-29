<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\Session;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Logout extends Page
{
    protected $defaultPageTitle = "Logout";
    protected $twigTemplate = "page-logout.twig";

    /** @var AvorgApi $api */
    private $api;

    /** @var Session $session */
    private $session;

    public function __construct(
        AvorgApi $api,
        Renderer $renderer,
        Router $router,
        Session $session,
        WordPress $wp
    )
    {
        parent::__construct($renderer, $router, $wp);

        $this->api = $api;
        $this->session = $session;
    }

    protected function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    protected function getPageData()
    {
        $this->session->unsetSession();
    }
}