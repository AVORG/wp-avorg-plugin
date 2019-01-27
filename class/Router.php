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
		$homePageId = $this->wp->get_option( "page_on_front");
		$mediaPageId = $this->wp->get_option( "avorgMediaPageId");
		$topicPageId = $this->wp->get_option( "avorgTopicPageId");

		$this->addRewriteTags();

		$pages = $this->pageFactory->getPages();

		array_map(function ($language) use ($mediaPageId, $homePageId, $topicPageId, $pages) {
			$this->addPageRewriteRules($pages, $language);
			$this->addHomePageRewriteRule($language, $homePageId);
		}, (array)$this->languages);
	}

	/**
	 * @param $language
	 * @param $homePageId
	 */
	public function addHomePageRewriteRule($language, $homePageId)
	{
		$route = $this->routeFactory->getPageRoute($homePageId, "{ language }");

		$this->wp->add_rewrite_rule(
			$route->getRouteRegex(),
			$route->getRedirect(),
			"top"
		);
	}

	private function addPageRewriteRules($pages, $language)
	{
		array_walk($pages, function(Page $page) use ($language) {
			$routeFormat = $page->getRoute();
			$translatedFormat = $this->translateFormat($language, $routeFormat);
			$pageId = $page->getPostId();
			$route = $this->routeFactory->getPageRoute($pageId, $translatedFormat);
			$regex = $route->getRouteRegex();
			$redirect = $route->getRedirect();

			$this->wp->add_rewrite_rule( $regex, $redirect, "top");
		});
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

	private function addRewriteTags()
	{
		$this->wp->add_rewrite_tag( "%entity_id%", "(\d+)");
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

        return "/" . $language["baseRoute"] . "/" .
            $language["urlFragments"]["sermons"] . "/" .
            $language["urlFragments"]["recordings"] . "/" .
            $apiRecording->id . "/" .
			$this->formatTitleForUrl($apiRecording) . ".html";
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