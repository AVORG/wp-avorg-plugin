<?php

final class TestPageRoute extends Avorg\TestCase
{
	/** @var \Avorg\Route\PageRoute $feed */
	protected $pageRoute;

	public function setUp()
	{
		parent::setUp();

		$this->pageRoute = $this->factory->make("Route\\PageRoute");
	}

	public function testExists()
	{
		$this->assertInstanceOf("\\Avorg\\Route\\PageRoute", $this->pageRoute);
	}

	/**
	 * @param $route
	 * @param array $matchables
	 * @param array $missables
	 * @dataProvider routeToRegexProvider
	 */
	public function testRouteToRegex($route, $matchables, $missables = [])
	{
		$this->pageRoute->setRoute($route);

		$regex = $this->pageRoute->getRouteRegex();

		array_walk($matchables, function ($matchable) use ($regex) {
			$this->assertRegExp("#$regex#", $matchable);
		});

		array_walk($missables, function ($missable) use ($regex) {
			$this->assertNotRegExp("#$regex#", $missable);
		});
	}

	public function routeToRegexProvider()
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

		$this->pageRoute->setRoute("$");

		$this->pageRoute->getRouteRegex();
	}
}