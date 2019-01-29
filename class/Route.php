<?php

namespace Avorg;

use Avorg\Route\RouteFragment;
use Avorg\Route\RouteFragment\RouteOption;

if (!\defined('ABSPATH')) exit;

abstract class Route
{
	/** @var Filesystem $filesystem */
	private $filesystem;

	private $languages;
	private $route;
	protected $routeTree;

	private $fragmentPatterns = [
		"variable" => "/^{(.+?)}/",
		"segment" => "/^(\w+)/",
		"separator" => "/^\//",
		"option" => "/^\[/"
	];

	public function __construct(Filesystem $filesystem)
	{
		$this->filesystem = $filesystem;

		$this->languages = json_decode($this->filesystem->getFile(AVORG_BASE_PATH . "/languages.json"), TRUE);
	}

	/**
	 * @param string $route
	 * @return Route
	 */
	public function setFormat($route)
	{
		$this->route = $route;
		$this->routeTree = $this->composeRouteTree($this->route);

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRewriteTags()
	{
		return array_merge(
			$this->getBaseTags(),
			$this->getTreeTags()
		);
	}

	protected function getBaseTags()
	{
		return [];
	}

	public function getRewriteRules()
	{
		$baseRegex = $this->getRegex();
		$baseRedirect = $this->getRedirect();

		return array_map(function($language) use($baseRegex, $baseRedirect) {
			return [
				"regex" => $this->translateFormat($language, $baseRegex),
				"redirect" => $baseRedirect
			];
		}, $this->languages ?: []);
	}

	/**
	 * @param $language
	 * @param $routeFormat
	 * @return mixed
	 */
	private function translateFormat($language, $routeFormat)
	{
		return array_reduce(array_keys($language["urlFragments"]), function ($carry, $key) use ($language) {
			$pattern = "/\b$key\b/";
			$replace = $language["urlFragments"][$key];

			if (!$replace) return $carry;

			return preg_replace($pattern, $replace, $carry);
		}, $routeFormat);
	}

	private function getRedirect()
	{
		$baseRedirect = $this->getBaseRoute();
		$queryVarString = $this->getQueryVarString();

		return $queryVarString ? "$baseRedirect&$queryVarString" : $baseRedirect;
	}

	abstract function getBaseRoute();

	private function getRegex()
	{
		$regex = array_reduce((array) $this->routeTree, function ($carry, $trunk) {
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
		$tokens = $this->getTreeTags();
		$tokenNames = array_keys($tokens);

		$queryVars = array_map(function ($key) use ($tokenNames) {
			$varName = $tokenNames[$key];
			$matchKey = $key + 1;

			return "$varName=\$matches[$matchKey]";
		}, array_keys($tokenNames));

		return implode("&", $queryVars);
	}

	/**
	 * @return mixed
	 */
	private function getTreeTags()
	{
		return array_reduce((array)$this->routeTree, function ($carry, RouteFragment $fragment) {
			return array_merge($carry, $fragment->getRewriteTags());
		}, []);
	}
}