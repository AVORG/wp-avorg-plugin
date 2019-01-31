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
	}
	
	public function registerRoutes()
	{
		$routes = $this->getRoutes();
		array_walk($routes, function($route) {
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
		return array_map(function(Language $language) {
			return $language->getRoute();
		}, $this->languageFactory->getLanguages());
	}

	private function getPageRoutes()
	{
		return array_map(function(Page $page) {
			return $page->getRoute();
		}, $this->pageFactory->getPages());
	}

	/**
	 * @return array
	 */
	private function getEndpointRoutes()
	{
		return array_map(function(Endpoint $endpoint) {
			return $endpoint->getRoute();
		}, $this->endpointFactory->getEndpoints());
	}

	/**
	 * @param $route
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

		array_walk($rules, function($rule) {
			$this->wp->add_rewrite_rule( $rule["regex"], $rule["redirect"], "top");
		});
	}
	
	public function setLocale($previous)
	{
		$requestUri = $_SERVER["REQUEST_URI"];
		$baseRoute  = explode("/", trim($requestUri, "/"))[0];
		$language   = $this->languageFactory->getLanguageByBaseRoute($baseRoute);

		return ($language) ? $language->getLangCode() : $previous;
	}

	public function filterRedirect($redirectUrl) {
	    $host           = $_SERVER["HTTP_HOST"];
		$requestUri     = $_SERVER["REQUEST_URI"];
		$path           = parse_url($requestUri, PHP_URL_PATH);
		$baseRoute      = explode("/", trim($path, "/"))[0];
		$language       = $this->languageFactory->getLanguageByBaseRoute($baseRoute);
		$fullRequestUri = $host . $path;

	    return $language ? "http://$fullRequestUri" : $redirectUrl;
    }

    public function getUrlForApiRecording($apiRecording)
    {
        $language = $this->languageFactory->getLanguageByLangCode($apiRecording->lang);

        if (!$language) return null;

        $fragments = [
			$language->getBaseRoute(),
			$language->translateUrlFragment("sermons"),
			$language->translateUrlFragment("recordings"),
			$apiRecording->id,
			$this->formatTitleForUrl($apiRecording) . ".html"
		];

		return "/" . implode("/", $fragments);
    }

	/**
	 * @param $apiRecording
	 * @return string
	 */
	private function formatTitleForUrl($apiRecording)
	{
		$title = $apiRecording->title;
		$titleLowerCase = strtolower($title);
		$titleNoPunctuation = preg_replace("/[^\w ]/", "", $titleLowerCase);
		$titleHyphenated = str_replace(" ", "-", $titleNoPunctuation);

		return $titleHyphenated;
	}
}