<?php

namespace Avorg;

use natlib\Factory;
use natlib\Stub;

class StubWordPress extends WordPress
{
    /** @var Factory $factory */
    private $factory;

    use Stub {
        __construct as protected traitConstruct;
        handleCall as protected traitHandleCall;
    }

    public function __construct(\PHPUnit\Framework\TestCase $testCase, Factory $factory)
    {
        $this->traitConstruct($testCase);

        $this->factory = $factory;
    }

    public function __call($function, $arguments)
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function get_all_meta_values($key)
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function get_all_query_vars()
    {
        return $this->handleCall(__FUNCTION__, func_get_args());
    }

    public function passCurrentPageCheck()
    {
        $this->setReturnValue("get_option", 100);
        $this->setCurrentPageId(100);
    }

    public function setCurrentPageToPage(Page $page)
    {
        $this->setSavedPageId($page, 7);
        $this->setCurrentPageId(7);
    }

    private function setCurrentPageId($id)
    {
        $this->setReturnValue("get_the_ID", $id);
        $this->setMappedReturnValues("get_query_var", [["page_id", $id]]);
    }

    public function setSavedPageId(Page $page, $id)
    {
        $optionName = $this->getPageIdOptionName($page);

        $this->setMappedReturnValues("get_option", [
            [$optionName, $id]
        ]);
    }

    public function assertPageCreated($title)
    {
        $this->assertMethodCalledWith("wp_insert_post", array(
            "post_title" => $title,
            "post_status" => "publish",
            "post_type" => "page"
        ), true);
    }

    public function assertPageNotCreated($content, $title)
    {
        $this->assertMethodNotCalledWith("wp_insert_post", array(
            "post_content" => $content,
            "post_title" => $title,
            "post_status" => "publish",
            "post_type" => "page"
        ), true);
    }

    /**
     * @param $pageName
     * @throws \ReflectionException
     */
    public function assertPageRegistered($pageName)
    {
        $pageObject = $this->factory->secure("Avorg\\Page\\$pageName");

        $this->assertFilterAdded("the_content", [$pageObject, "addUi"]);
    }

    public function getPageIdOptionName(Page $page)
    {
        $prefix = "avorg_page_id_";
        $class = get_class($page);
        $lowercase = strtolower($class);
        $slashToUnderscore = str_replace("\\", "_", $lowercase);

        return $prefix . $slashToUnderscore;
    }

    public function assertRestRouteRegistered($route)
    {
        $this->assertAnyCallMatches('register_rest_route', function ($call) use ($route) {
            return $call[1] === $route;
        }, "Failed asserting that route '$route' was registered");
    }

    public function assertRestRouteQueryVarsRegistered($method, $vars)
    {
        $this->assertAnyCallMatches("register_rest_route", function ($call) use ($method, $vars) {
            $methodOpts = array_filter($call[2], function ($optSet) use ($method) {
                return $optSet['methods'] === $method;
            })[0];

            return $methodOpts['args'] === $vars;
        });
    }

    /**
     * @param $tag
     * @param $callable
     */
    public function assertFilterAdded($tag, $callable)
    {
        $this->assertMethodCalledWith(
            "add_filter",
            $tag,
            $callable
        );
    }

    /**
     * @param $tag
     * @param $callable
     * @param bool $priority
     */
    public function assertActionAdded($tag, $callable, $priority = False)
    {
        if ($priority) {
            $this->assertMethodCalledWith("add_action", $tag, $callable, $priority);
        } else {
            $this->assertMethodCalledWith("add_action", $tag, $callable);
        }
    }

    public function runActions(...$actions)
    {
        array_walk($actions, function ($action) {
            $this->runAction($action);
        });
    }

    /**
     * @param $action
     * @return array
     */
    private function runAction($action, ...$args)
    {
        $calls = $this->getCalls("add_action");

        $filteredCalls = array_filter($calls, function ($call) use ($action) {
            return $call[0] === $action;
        });

        return array_map(function ($call) use ($args) {
            call_user_func($call[1], ...$args);
        }, $filteredCalls);
    }

    public function runFilter($filter, ...$args)
    {
        $calls = $this->getCalls("add_filter");

        $filteredCalls = array_filter($calls, function ($call) use ($filter) {
            return $call[0] === $filter;
        });

        return array_map(function ($call) use ($args) {
            return call_user_func($call[1], ...$args);
        }, $filteredCalls);
    }

    public function runActivationHook(...$args)
    {
        $calls = $this->getCalls("register_activation_hook");

        return array_map(function ($call) use ($args) {
            return call_user_func($call[1], ...$args);
        }, $calls);
    }
}