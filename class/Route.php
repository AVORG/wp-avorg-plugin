<?php

namespace Avorg;

use Avorg\Route\RouteFragment;
use Avorg\Route\RouteFragment\RouteOption;
use Exception;

if (!\defined('ABSPATH')) exit;

abstract class Route
{
	/** @var Filesystem $filesystem */
	private $filesystem;

	/** @var LanguageFactory $languageFactory */
	protected $languageFactory;

	protected $routeFormat;
	protected $routeTree;
	protected $id;

	private $fragmentPatterns = [
		"variable" => "/^{(.+?)}/",
		"segment" => "/^(\w+)/",
		"separator" => "/^\//",
		"option" => "/^\[/"
	];

	public function __construct(Filesystem $filesystem, LanguageFactory $languageFactory)
	{
		$this->filesystem = $filesystem;
		$this->languageFactory = $languageFactory;
	}

	/**
	 * @param mixed $id
	 * @return Route
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @param string $routeFormat
	 * @return Route
	 */
	public function setFormat($routeFormat)
	{
		$this->routeFormat = $routeFormat;
		$this->routeTree = $this->composeRouteTree($this->routeFormat);

		return $this;
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function getRewriteTags()
	{
		$this->validateRoute();

		return array_merge(
			$this->getBaseTags(),
			$this->getTreeTags()
		);
	}

	protected function getBaseTags()
	{
		return [];
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function getRewriteRules()
	{
		$this->validateRoute();

		$baseRegex = $this->getRegex();
		$baseRedirect = $this->getRedirect();
		$languages = $this->languageFactory->getLanguages();

		return array_map(function($language) use($baseRegex, $baseRedirect) {
			return [
				"regex" => $this->translateFormat($language, $baseRegex),
				"redirect" => $baseRedirect
			];
		}, $languages);
	}

	private function validateRoute()
	{
		if (!$this->routeFormat) throw new Exception("No route format provided");
	}

	/**
	 * @param $language
	 * @param $routeFormat
	 * @return mixed
	 */
	private function translateFormat(Language $language, $routeFormat)
	{
		$fragments = $language->getUrlFragments();
		return array_reduce(array_keys($fragments), function ($carry, $key) use ($fragments) {
			$pattern = "/\b$key\b/";
			$replace = $fragments[$key];

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
	 * @throws Exception
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
	 * @throws Exception
	 */
	private function throwInvalidRouteException($route)
	{
		throw new Exception("Invalid route $this->routeFormat. Failed to continue at $route");
	}

	/**
	 * @return array
	 */
	private function getQueryVarString()
	{
		$tokens = $this->getTreeTags();
		$tokenNames = array_keys($tokens);

		$queryVars = array_map(function ($key) use ($tokenNames) {
			$varName = $tokenNames[$key];
			$matchKey = $key + 1;

			return $this->formatQueryVar($varName, $matchKey);
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

	protected function formatQueryVar($queryKey, $matchKey)
	{
		return "$queryKey=\$matches[$matchKey]";
	}
}