<?php

namespace Avorg\Page;

use Avorg\Page;
use function defined;

if (!defined('ABSPATH')) exit;

class Search extends Page
{
    protected $defaultPageTitle = "Search";
    protected $twigTemplate = "page-search.twig";

    public function registerCallbacks()
    {
        parent::registerCallbacks();

        $this->wp->add_filter('query_vars', [$this, 'registerQueryVar']);
    }

    public function registerQueryVar($vars)
    {
        $vars[] = 'q';
        return $vars;
    }

    protected function getTitle()
    {
        // TODO: Implement getTitle() method.
    }

    protected function getPageData()
    {
        return [
            'query' => $this->wp->get_query_var('q')
        ];
    }
}