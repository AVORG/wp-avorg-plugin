<?php

use Avorg\Route\PageRoute;

final class TestPageRoute extends Avorg\TestCase
{
	/** @var PageRoute $feed */
	protected $pageRoute;

	public function setUp(): void
	{
		parent::setUp();

		$this->pageRoute = $this->factory->make("Avorg\\Route\\PageRoute");
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Route\\PageRoute", $this->pageRoute);
	}

	/**
	 * @param $route
	 * @param array $matchables
	 * @param array $missables
	 * @dataProvider routeAndRequestsProvider
	 * @throws Exception
	 */
	public function testRouteMatchesCorrectRequests($route, $matchables, $missables = [])
	{
		$this->pageRoute->setFormat($route);

		$regex = $this->pageRoute->getRewriteRules()["English"]["regex"];

		array_walk($matchables, function ($matchable) use ($regex) {
			$this->assertRegExp("#$regex#", $matchable);
		});

		array_walk($missables, function ($missable) use ($regex) {
			$this->assertNotRegExp("#$regex#", $missable);
		});
	}

	public function routeAndRequestsProvider()
	{
		return [
			"static" => [
				"hello/world",
				[
					"hello/world",
					"hello/world/"
				],
				[
					"not/this"
				]
			],
			"tokens" => [
				"hello/{ name }",
				[
					"hello/bob",
					"hello/bob/",
					"hello/frank"
				],
				[
					"hey/hello/bob",
					"hello/bob/simons"
				]
			],
			"regex tokens" => [
				"item/{ id:[0-9]+ }",
				[
					"item/1234",
					"item/1234/"
				]
			],
			"optional fragments" => [
				"this/or[/that]",
				[
					"this/or/that/",
					"this/or/that",
					"this/or/",
					"this/or"
				]
			],
			"nested optional fragments" => [
				"this[/or[/that]]",
				[
					"this/or/that/",
					"this/or/that",
					"this/or/",
					"this/or",
					"this/",
					"this"
				]
			],
			"sibling optional fragments" => [
				"[this/]or[/that]",
				[
					"this/or/that/",
					"this/or/that",
					"or/that/",
					"or/that",
					"this/or/",
					"this/or",
					"or/",
					"or"
				]
			]
		];
	}

	public function testThrowsExceptionIfInvalidFragment()
	{
		$this->expectException(\Exception::class);

		$this->pageRoute->setFormat("$");

		$this->pageRoute->getRewriteRules();
	}

	/**
	 * @dataProvider routeRedirectsProvider
	 * @param $routePattern
	 * @param $inputUrl
	 * @param $outputUrl
	 * @throws Exception
	 */
	public function testRouteRedirects($routePattern, $inputUrl, $outputUrl)
	{
		$this->pageRoute->setFormat($routePattern)->setId("PAGE_ID");

		$pairs = $this->pageRoute->getRewriteRules();

		$result = array_reduce($pairs, function($carry, $pair) use($inputUrl, $outputUrl) {
			$regex = $pair["regex"];
			$redirect = $pair["redirect"];

			preg_match("/$regex/", $inputUrl, $matches);

			try {
                $result = eval("return \"$redirect\";");
            } catch (Exception $e) {
			    $result = null;
            }

			return $carry || $result === $outputUrl;
		}, false);

		$this->assertTrue($result);
	}

	public function routeRedirectsProvider()
	{
		return [
			"standard presentation route" => [
				"english/sermons/recordings/{ entity_id:[0-9]+ }[/{ slug }]",
				"english/sermons/recordings/316/parents-how.html",
				"index.php?page_id=PAGE_ID&entity_id=316&slug=parents-how.html"
			],
			"optional token" => [
				"route/with[/{ token }]",
				"route/with/token",
				"index.php?page_id=PAGE_ID&token=token"
			],
			"no tokens" => [
				"route/to/page",
				"route/to/page",
				"index.php?page_id=PAGE_ID"
			]
		];
	}

	/**
	 * @throws Exception
	 */
	public function testThrowsExceptionIfNoFormat()
	{
		$this->expectException(Exception::class);

		$this->pageRoute->getRewriteRules();
	}

	/**
	 * @throws Exception
	 */
	public function testThrowsExceptionIfNoFormatWhenGettingRewriteTags()
	{
		$this->expectException(Exception::class);

		$this->pageRoute->getRewriteTags();
	}

	public function testGetPath()
	{
		$this->pageRoute->setFormat("item/{ id:[0-9]+ }");

		$url = $this->pageRoute->getPath([
			"id" => 5
		]);

		$this->assertEquals("item/5", $url);
	}
}