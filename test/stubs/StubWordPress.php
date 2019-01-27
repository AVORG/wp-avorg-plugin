<?php

namespace Avorg;

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

	public function __call( $function, $arguments )
	{
		return $this->handleCall(__FUNCTION__, func_get_args());
	}

	public function setCurrentPageToPage($page)
	{
		$this->setSavedPageId($page, 7);
		$this->setCurrentPageId(7);
	}

	public function setCurrentPageId($id)
	{
		$this->setReturnValue("get_the_ID", $id);
	}

	public function setSavedPageId($page, $id)
	{
		$optionName = $this->getPageIdOptionName($page);

		$this->setMappedReturnValues("get_option", [
			[ $optionName, $id ]
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
		$pageObject = $this->factory->get("Page\\$pageName");

		$this->assertMethodCalledWith(
			"add_filter",
			"the_content",
			[$pageObject, "addUi"]
		);
	}

	public function getPageIdOptionName($page)
	{
		$prefix = "avorg_page_id_";
		$class = get_class($page);
		$lowercase = strtolower($class);
		$slashToUnderscore = str_replace("\\", "_", $lowercase);

		return $prefix . $slashToUnderscore;
	}
}