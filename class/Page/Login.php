<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
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

    public function __construct(AvorgApi $api, Renderer $renderer, Router $router, WordPress $wp)
    {
        parent::__construct($renderer, $router, $wp);

        $this->api = $api;
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

        $_SESSION['user'] = $this->api->logIn($_POST['email'], $_POST['password']);
    }
}