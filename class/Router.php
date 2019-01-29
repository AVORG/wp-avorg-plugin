<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Router
{
	/** @var EndpointFactory $endpointFactory */
	private $endpointFactory;

	/** @var Filesystem $filesystem */
	private $filesystem;

	/** @var PageFactory $pageFactory */
	private $pageFactory;

	/** @var RouteFactory $routeFactory */
	private $routeFactory;
	
	/** @var WordPress $wp */
	private $wp;
	
	private $languages;
	
	public function __construct(
		EndpointFactory $endpointFactory,
		Filesystem $filesystem,
		PageFactory $pageFactory,
		RouteFactory $routeFactory,
		WordPress $WordPress
	)
	{
		$this->endpointFactory = $endpointFactory;
		$this->filesystem = $filesystem;
		$this->pageFactory = $pageFactory;
		$this->routeFactory = $routeFactory;
		$this->wp = $WordPress;
		
		$this->languages = json_decode($this->filesystem->getFile(AVORG_BASE_PATH . "/languages.json"), TRUE);
	}
	
	public function activate()
	{
		$this->registerRoutes();
		$this->wp->flush_rewrite_rules();
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
			[$this->getHomeRoute()],
			$this->getPageRoutes(),
			$this->getEndpointRoutes()
		);
	}

	private function getHomeRoute()
	{
		$homePageId = $this->wp->get_option( "page_on_front");

		return $this->routeFactory->getPageRoute($homePageId, "{ language }");
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
	
	public function setLocale($lang)
	{
		$requestUri = $_SERVER["REQUEST_URI"];
		
		$newLang = array_reduce((array)$this->languages, function ($carry, $language) use ($requestUri) {
			$substringLength = strlen($language["baseRoute"]);
			$trimmedUri = trim($requestUri, "/");
			$baseRoute = substr($trimmedUri, 0, $substringLength);
			$doesBaseRouteMatch = $baseRoute === $language["baseRoute"];
			
			return $doesBaseRouteMatch ? $language["wpLanguageCode"] : $carry;
		}, $lang);
		
		return $newLang;
	}

	public function filterRedirect($redirectUrl) {
	    $host = $_SERVER["HTTP_HOST"];
	    $requestUri = $_SERVER["REQUEST_URI"];
	    $path = parse_url($requestUri, PHP_URL_PATH);
        $fullRequestUri = $host . $path;
	    $fragment = strtolower(explode("/", $path)[1]);
	    $isFragmentLanguage = array_reduce((array) $this->languages, function($carry, $language) use($fragment) {
	        return $carry || $fragment === strtolower($language["baseRoute"]);
        }, FALSE);

	    return $isFragmentLanguage ? "http://$fullRequestUri" : $redirectUrl;
    }

    public function getUrlForApiRecording($apiRecording)
    {
        $filteredLanguages = array_filter((array)$this->languages, function ($language) use ($apiRecording) {
            return $language["dbCode"] === $apiRecording->lang;
        });
        $language = reset($filteredLanguages);

        $fragments = [
			$language["baseRoute"],
			$language["urlFragments"]["sermons"],
			$language["urlFragments"]["recordings"],
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