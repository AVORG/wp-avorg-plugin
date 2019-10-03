<?php

namespace Avorg\Page;

use Avorg\Page;
use function defined;

if (!defined('ABSPATH')) exit;

class Login extends Page
{
    protected $defaultPageTitle = "Login";
    protected $defaultPageContent = "Login";
    protected $twigTemplate = "page-login.twig";

    protected function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    protected function getPageData()
    {
        // TODO: Implement getPageData() method.
    }
}