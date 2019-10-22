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

class Login extends Page
{
    protected $defaultPageTitle = "Login";
    protected $defaultPageContent = "Login";
    protected $twigTemplate = "page-login.twig";

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
        $email = $_POST['email'] ?? false;
        $password = $_POST['password'] ?? false;

        if (!($email && $password)) return;

        $sessionData = $this->api->logIn($_POST['email'], $_POST['password']);

        $this->session->loadData($sessionData);
    }
}