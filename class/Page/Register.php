<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use Exception;
use function defined;

if (!defined('ABSPATH')) exit;

class Register extends Page
{
    protected $defaultPageTitle = "Register";
    protected $twigTemplate = "page-register.twig";

    /** @var AvorgApi $api */
    private $api;

    public function __construct(
        AvorgApi $api,
        Renderer $renderer,
        Router $router,
        WordPress $wp
    )
    {
        parent::__construct($renderer, $router, $wp);

        $this->api = $api;
    }

    protected function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    /**
     * @return array
     * @throws Exception
     */
    protected function getPageData()
    {
        if (!$this->isRequestValid()) return;

        $lang = $this->router->getRequestLanguage();

        $response = $this->api->register(
            $_POST['email'],
            $_POST['password'],
            $_POST['password2'],
            $lang->getDbCode()
        );

        return [
            'success' => $response === True,
            'errors' => $this->getErrorMessages($response)
        ];
    }

    private function getErrorMessages($response)
    {
        if (!is_array($response)) return [];

        return array_reduce($response, function($carry, $group) {
            return array_merge($carry, $group);
        }, []);
    }

    /**
     * @return bool
     */
    private function isRequestValid()
    {
        if (!array_key_exists('email', $_POST)) return False;
        if (!array_key_exists('password', $_POST)) return False;
        if (!array_key_exists('password2', $_POST)) return False;

        return True;
    }
}