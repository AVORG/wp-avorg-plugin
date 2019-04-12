<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Router
{
	/** @var EndpointFactory $endpointFactory */
	private $endpointFactory;

	/** @var LanguageFactory $languageFactory */
	private $languageFactory;

	/** @var PageFactory $pageFactory */
	private $pageFactory;

	/** @var RouteFactory $routeFactory */
	private $routeFactory;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
		EndpointFactory $endpointFactory,
		LanguageFactory $languageFactory,
		PageFactory $pageFactory,
		RouteFactory $routeFactory,
		WordPress $WordPress
	)
	{
		$this->endpointFactory = $endpointFactory;
		$this->languageFactory = $languageFactory;
		$this->pageFactory = $pageFactory;
		$this->routeFactory = $routeFactory;
		$this->wp = $WordPress;
	}

	public function activate()
	{
		$this->registerRoutes();
		$this->wp->flush_rewrite_rules();
	}

	public function registerCallbacks()
	{
		$this->wp->add_filter("locale", array($this, "setLocale"));
		$this->wp->add_filter("redirect_canonical", array($this, "filterRedirect"));
	}

	public function registerRoutes()
	{
		$routes = $this->getRoutes();
		array_walk($routes, function ($route) {
			$this->addRewriteTags($route);
			$this->addRewriteRules($route);
		});
	}

	/**
	 * @return array
	 */
	private function getRoutes()
	{
		return array_merge(
			$this->getLanguageRoutes(),
			$this->getPageRoutes(),
			$this->getEndpointRoutes()
		);
	}

	private function getLanguageRoutes()
	{
		return array_map(function (Language $language) {
			return $language->getRoute();
		}, $this->languageFactory->getLanguages());
	}

	private function getPageRoutes()
	{
		return array_map(function (Page $page) {
			return $page->getRoute();
		}, $this->pageFactory->getPages());
	}

	/**
	 * @return array
	 */
	private function getEndpointRoutes()
	{
		return array_map(function (Endpoint $endpoint) {
			return $endpoint->getRoute();
		}, $this->endpointFactory->getEndpoints());
	}

	/**
	 * @param $route
	 * @throws \Exception
	 */
	private function addRewriteTags(Route $route)
	{
		$tags = $route->getRewriteTags();
		$keys = array_keys($tags);
		array_walk($keys, function ($key) use ($tags) {
			$this->wp->add_rewrite_tag("%$key%", $tags[$key]);
		});
	}

	private function addRewriteRules(Route $route)
	{
		$rules = $route->getRewriteRules();

		Logger::log(var_export($rules, true));

		array_walk($rules, function ($rule) {
			$this->wp->add_rewrite_rule($rule["regex"], $rule["redirect"], "top");
		});
	}

	public function setLocale($previous)
	{
		$language = $this->getRequestLanguage();

		return ($language) ? $language->getLangCode() : $previous;
	}

	public function filterRedirect($redirectUrl)
	{
		$language = $this->getRequestLanguage();

		Logger::log(
			"Filter redirect. " .
			"Request: " . var_export($redirectUrl, TRUE) . "; " .
			"Should cancel: " . var_export((bool)$language, TRUE)
		);

		return $language ? $this->getFullRequestUri() : $redirectUrl;
	}

	public function getRequestLanguage()
	{

		$baseRoute = explode("/", trim($this->getRequestPath(), "/"))[0];

		return $this->languageFactory->getLanguageByBaseRoute($baseRoute);
	}

	/**
	 * @return string
	 */
	public function getFullRequestUri()
	{
		return "http://" . $_SERVER["HTTP_HOST"] . $this->getRequestPath();
	}

	private function getRequestPath()
	{
		return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
	}
}
