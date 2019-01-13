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
}