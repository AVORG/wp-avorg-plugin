<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Router
{
	/** @var Filesystem $filesystem */
	private $filesystem;
	
	/** @var WordPress $wp */
	private $wp;
	
	private $languages;
	
	public function __construct(Filesystem $filesystem, WordPress $WordPress)
	{
		$this->filesystem = $filesystem;
		$this->wp = $WordPress;
		
		$this->languages = json_decode($this->filesystem->getFile(AVORG_BASE_PATH . "/languages.json"));
	}
	
	public function activate()
	{
		$this->addRewriteRules();
		$this->wp->call("flush_rewrite_rules");
	}
	
	public function addRewriteRules()
	{
		$mediaPageId = $this->wp->call("get_option", "avorgMediaPageId");
		$homePageId = $this->wp->call("get_option", "page_on_front");
		$this->wp->call("add_rewrite_tag", "%presentation_id%", "(\d+)");
		
		array_map(function ($language) use ($mediaPageId, $homePageId) {
			$this->addMediaPageRewriteRule(
				$mediaPageId,
				$language->baseRoute,
				$language->modules->sermons,
				$language->controllers->recordings
			);
			
			$this->addHomePageRewriteRule($language, $homePageId);
		}, (array)$this->languages);
	}
	
	public function addMediaPageRewriteRule($mediaPageId, $languageTrans, $sermonsTrans, $recordingsTrans)
	{
		$regex = "^$languageTrans\/$sermonsTrans\/$recordingsTrans\/(\d+)\/[\w-\.]+\/?";
		$redirect = "index.php?page_id=$mediaPageId&presentation_id=\$matches[1]";
		$priority = "top";
		
		$this->wp->call("add_rewrite_rule", $regex, $redirect, $priority);
	}
	
	/**
	 * @param $language
	 * @param $homePageId
	 */
	public function addHomePageRewriteRule($language, $homePageId)
	{
		$this->wp->call(
			"add_rewrite_rule",
			"^$language->baseRoute",
			"index.php?page_id=$homePageId",
			"top"
		);
	}
	
	public function setLocale($lang)
	{
		$requestUri = $_SERVER["REQUEST_URI"];
		
		$newLang = array_reduce((array)$this->languages, function ($carry, $language) use ($requestUri) {
			$substringLength = strlen($language->baseRoute);
			$trimmedUri = trim($requestUri, "/");
			$baseRoute = substr($trimmedUri, 0, $substringLength);
			$doesBaseRouteMatch = $baseRoute === $language->baseRoute;
			
			return $doesBaseRouteMatch ? $language->wpLanguageCode : $carry;
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
	        return $carry || $fragment === strtolower($language->baseRoute);
        }, FALSE);

	    return $isFragmentLanguage ? "http://$fullRequestUri" : $redirectUrl;
    }
}