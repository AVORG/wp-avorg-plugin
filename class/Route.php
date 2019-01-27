<?php

namespace Avorg;

use Avorg\Route\RouteFragment;
use Avorg\Route\RouteFragment\RouteOption;

if (!\defined('ABSPATH')) exit;

abstract class Route
{
	private $route;
	protected $routeTree;

	private $fragmentPatterns = [
		"variable" => "/^{(.+?)}/",
		"segment" => "/^(\w+)/",
		"separator" => "/^\//",
		"option" => "/^\[/"
	];

	/**
	 * @param string $route
	 * @return Route
	 */
	public function setRoute($route)
	{
		$this->route = $route;
		$this->routeTree = $this->composeRouteTree($this->route);

		return $this;
	}

	public function getRedirect()
	{
		$baseRedirect = $this->getBaseRoute();
		$queryVarString = $this->getQueryVarString();

		return $queryVarString ? "$baseRedirect&$queryVarString" : $baseRedirect;
	}

	abstract function getBaseRoute();

	public function getRouteRegex()
	{
		$regex = array_reduce($this->routeTree, function ($carry, $trunk) {
			return $carry . $trunk->getRegex();
		}, "");

		return "^$regex\/?$";
	}

	private function composeRouteTree($route, $tree = [])
	{
		while ($route) {
			$prevRoute = $route;
			$type = $this->getNextFragmentType($route);

			if ($type === false) {
				$this->throwInvalidRouteException($route);
			} elseif ($type === "option") {
				$tree[] = $this->getOption($route);
				$route = $this->deleteOption($route);
			} else {
				$tree[] = $this->getTerminalFragment($route, $type);
				$route = $this->deleteFragment($route, $type);
			}

			if ($route === $prevRoute) {
				$this->throwInvalidRouteException($route);
			}
		}

		return $tree;
	}

	private function getNextFragmentType($route)
	{
		return array_reduce(array_keys($this->fragmentPatterns), function($carry, $key) use($route) {
			return $this->startsWithFragment($route, $key) ? $key : $carry;
		}, false);
	}

	/**
	 * @param $route
	 * @param $type
	 * @return bool
	 */
	private function startsWithFragment($route, $type)
	{
		return preg_match($this->fragmentPatterns[$type], $route) === 1;
	}

	/**
	 * @param $route
	 * @return RouteOption
	 * @throws \Exception
	 */
	private function getOption($route)
	{
		$rawContent = $this->getRawOptionContent($route);
		$content = $this->composeRouteTree($rawContent);

		return new RouteOption($content);
	}

	private function getRawOptionContent($route)
	{
		$closingBracketLocation = $this->getClosingBracketLocation($route);

		return trim(substr($route, 1, $closingBracketLocation - 1));
	}

	private function deleteOption($route)
	{
		$closingBracketLocation = $this->getClosingBracketLocation($route);

		return substr($route, $closingBracketLocation + 1);
	}

	private function getClosingBracketLocation($route)
	{
		$chars = str_split($route);
		$level = 0;
		foreach ($chars as $i => $char) {
			if ($char === "[") $level++;
			if ($char === "]") $level--;
			if ($level === 0) return $i;
		}

		return -1;
	}

	/**
	 * @param $route
	 * @param $type
	 * @return null|string|string[]
	 */
	private function deleteFragment($route, $type)
	{
		return preg_replace($this->fragmentPatterns[$type], "", $route);
	}

	/**
	 * @param $route
	 * @param $type
	 * @return RouteFragment
	 */
	private function getTerminalFragment($route, $type)
	{
		$content = $this->getRawFragmentContent($route, $type);
		$className = "\\Avorg\\Route\\RouteFragment\\Route" . ucfirst($type);

		return new $className($content);
	}

	/**
	 * @param $route
	 * @param $type
	 * @return string
	 */
	private function getRawFragmentContent($route, $type)
	{
		preg_match($this->fragmentPatterns[$type], $route, $matches);
		$content = (count($matches) > 1) ? $matches[1] : "";

		return trim($content);
	}

	/**
	 * @param $route
	 * @throws \Exception
	 */
	private function throwInvalidRouteException($route)
	{
		throw new \Exception("Invalid route $this->route. Failed to continue at $route");
	}

	/**
	 * @return array
	 */
	protected function getQueryVarString()
	{
		$tokens = array_reduce($this->routeTree, function($carry, RouteFragment $fragment) {
			return array_merge($carry, $fragment->getRedirectTokens());
		}, []);

		$queryVars = array_map(function ($key) use ($tokens) {
			$varName = $tokens[$key];
			$matchKey = $key + 1;

			return "$varName=\$matches[$matchKey]";
		}, array_keys($tokens));

		return implode("&", $queryVars);
	}
}