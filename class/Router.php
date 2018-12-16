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
		$homePageId = $this->wp->call("get_option", "page_on_front");
		$mediaPageId = $this->wp->call("get_option", "avorgMediaPageId");
		$topicPageId = $this->wp->call("get_option", "avorgTopicPageId");

		$this->addRewriteTags();

		array_map(function ($language) use ($mediaPageId, $homePageId, $topicPageId) {
			$this->addMediaPageRewriteRule(
				$mediaPageId,
				$language->baseRoute,
				$language->modules->sermons,
				$language->controllers->recordings
			);

			$this->addTopicPageRewriteRule(
				$topicPageId,
				$language->baseRoute,
				$language->modules->topics
			);
			
			$this->addHomePageRewriteRule($language, $homePageId);
		}, (array)$this->languages);
	}

	private function addRewriteTags()
	{
		$this->wp->call("add_rewrite_tag", "%presentation_id%", "(\d+)");
		$this->wp->call("add_rewrite_tag", "%topic_id%", "(\d+)");
	}
	
	public function addMediaPageRewriteRule($mediaPageId, $languageTrans, $sermonsTrans, $recordingsTrans)
	{
		$regex = "^$languageTrans\/$sermonsTrans\/$recordingsTrans\/(\d+)\/[\w-\.]+\/?";
		$redirect = "index.php?page_id=$mediaPageId&presentation_id=\$matches[1]";
		$priority = "top";
		
		$this->wp->call("add_rewrite_rule", $regex, $redirect, $priority);
	}

	public function addTopicPageRewriteRule($topicPageId, $languageTrans, $topicsTrans)
	{
		$regex = "^$languageTrans\/$topicsTrans\/(\d+)\/[\w-\.]+\/?";
		$redirect = "index.php?page_id=$topicPageId&topic_id=\$matches[1]";
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

    public function getUrlForApiRecording($apiRecording)
    {
        $filteredLanguages = array_filter((array)$this->languages, function ($language) use ($apiRecording) {
            return $language->dbCode === $apiRecording->lang;
        });
        $language = reset($filteredLanguages);

        return "/" . $language->baseRoute . "/" .
            $language->modules->sermons . "/" .
            $language->controllers->recordings . "/" .
            $apiRecording->id . "/" .
            rawurlencode($apiRecording->title) . ".html";
    }
}