<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Router
{
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
		Filesystem $filesystem,
		PageFactory $pageFactory,
		RouteFactory $routeFactory,
		WordPress $WordPress
	)
	{
		$this->filesystem = $filesystem;
		$this->pageFactory = $pageFactory;
		$this->routeFactory = $routeFactory;
		$this->wp = $WordPress;
		
		$this->languages = json_decode($this->filesystem->getFile(AVORG_BASE_PATH . "/languages.json"), TRUE);
	}
	
	public function activate()
	{
		$this->addRewriteRules();
		$this->wp->flush_rewrite_rules();
	}
	
	public function addRewriteRules()
	{
		$this->addHomePageRewriteRule();

		$pages = $this->pageFactory->getPages();
		array_map(function ($language) use ($pages) {
			$this->addPageRewriteRules($pages, $language);
		}, (array)$this->languages);
	}

	public function addHomePageRewriteRule()
	{
		$homePageId = $this->wp->get_option( "page_on_front");
		$route = $this->routeFactory->getPageRoute($homePageId, "{ language }");
		$regex = $route->getRegex();
		$redirect = $route->getRedirect();

		$this->wp->add_rewrite_rule(
			$regex,
			$redirect,
			"top"
		);
	}

	private function addPageRewriteRules($pages, $language)
	{
		array_walk($pages, function(Page $page) use ($language) {
			$routeFormat = $this->translateFormat($language, $page->getRouteFormat());
			$route = $this->routeFactory->getPageRoute($page->getPostId(), $routeFormat);

			$this->addRewriteTags($route);
			$this->addRewriteRule($route);
		});
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

	private function addRewriteRule(Route $route)
	{
		$regex = $route->getRegex();
		$redirect = $route->getRedirect();
		$this->wp->add_rewrite_rule( $regex, $redirect, "top");
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