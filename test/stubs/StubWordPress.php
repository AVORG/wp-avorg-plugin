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

	public function setCurrentPageToPage(Page $page)
	{
		$this->setSavedPageId($page, 7);
		$this->setCurrentPageId(7);
	}

	public function setCurrentPageId($id)
	{
		$this->setReturnValue("get_the_ID", $id);
	}

	public function setSavedPageId(Page $page, $id)
	{
		$optionName = $this->getPageIdOptionName($page);

		$this->setMappedReturnValues("get_option", [
			[$optionName, $id]
		]);
	}

	public function assertPageCreated($content, $title)
	{
		$this->assertMethodCalledWith("wp_insert_post", array(
			"post_content" => $content,
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
		$pageObject = $this->factory->secure("Page\\$pageName");

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
	 */
	public function assertActionAdded($tag, $callable)
	{
		$this->assertMethodCalledWith(
			"add_action",
			$tag,
			$callable
		);
	}

	public function runActions(...$actions)
	{
		array_walk($actions, function ($action) {
			$this->runAction($action);
		});
	}

	/**
	 * @param $action
	 */
	private function runAction($action)
	{
		$calls = $this->getCalls("add_action");

		$filteredCalls = array_filter($calls, function ($call) use ($action) {
			return $call[0] === $action;
		});

		array_map(function ($call) {
			call_user_func($call[1]);
		}, $filteredCalls);
	}
}