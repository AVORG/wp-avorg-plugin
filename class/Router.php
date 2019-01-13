<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

define("AVORG_BASE_ROUTE_TOKEN", "{base_route}");
define("AVORG_ENTITY_ID_TOKEN", "{entity_id}");
define("AVORG_VARIABLE_FRAGMENT_TOKEN", "{variable_fragment}");

class Router
{
	/** @var Filesystem $filesystem */
	private $filesystem;

	/** @var PageFactory $pageFactory */
	private $pageFactory;
	
	/** @var WordPress $wp */
	private $wp;
	
	private $languages;
	
	public function __construct(
		Filesystem $filesystem,
		PageFactory $pageFactory,
		WordPress $WordPress
	)
	{
		$this->filesystem = $filesystem;
		$this->pageFactory = $pageFactory;
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

	private function addPageRewriteRules($pages, $language)
	{
		array_walk($pages, function(Page $page) use ($language) {
			$route = $page->getRoute();

			if (strstr($route, AVORG_ENTITY_ID_TOKEN) === FALSE) {
				throw new \Exception("Missing entity ID token in route");
			}

			$regex = $this->prepareRewriteRegex($route, $language);
			$pageId = $page->getPostId();
			$redirect = "index.php?page_id=$pageId&entity_id=\$matches[1]";

			$this->wp->add_rewrite_rule( $regex, $redirect, "top");
		});
	}

	/**
	 * @param $route
	 * @param $language
	 * @return mixed|string
	 */
	private function prepareRewriteRegex($route, $language)
	{
		$route = str_replace("/", "\/", $route);
		$route = str_replace(AVORG_BASE_ROUTE_TOKEN, $language["baseRoute"], $route);
		$route = array_reduce(array_keys($language["urlFragments"]), function ($carry, $key) use ($language) {
			$pattern = "/\b$key\b/";
			$replace = $language["urlFragments"][$key];

			if (!$replace) return $carry;

			return preg_replace($pattern, $replace, $carry);
		}, $route);
		$route = str_replace(AVORG_ENTITY_ID_TOKEN, "(\d+)", $route);
		$route = str_replace(AVORG_VARIABLE_FRAGMENT_TOKEN, "[\w-\.]+", $route);

		return "^$route\/?";
	}

	private function addRewriteTags()
	{
		$this->wp->add_rewrite_tag( "%entity_id%", "(\d+)");
	}
	
	/**
	 * @param $language
	 * @param $homePageId
	 */
	public function addHomePageRewriteRule($language, $homePageId)
	{
		$this->wp->add_rewrite_rule(
			"^" . $language["baseRoute"],
			"index.php?page_id=$homePageId",
			"top");
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