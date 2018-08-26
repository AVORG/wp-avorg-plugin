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
	
	public function __construct( Filesystem $filesystem, WordPress $WordPress)
	{
		$this->filesystem = $filesystem;
		$this->wp = $WordPress;
		
		$this->wp->call("add_action", "parse_query", [$this, "handleQuery"]);
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
		$this->wp->call("add_rewrite_tag", "%presentation_id%", "(\d+)");
		
		$this->addLocalizedRewriteRule(
			$mediaPageId,
			"english",
			"sermons",
			"recordings"
		);
		
		$this->addLocalizedRewriteRule(
			$mediaPageId,
			"espanol",
			"sermones",
			"grabaciones"
		);
		
		$this->addLocalizedRewriteRule(
			$mediaPageId,
			"francais",
			"predications",
			"enregistrements"
		);
	}
	
	public function addLocalizedRewriteRule($mediaPageId, $languageTrans, $sermonsTrans, $recordingsTrans)
	{
		$regex = "^$languageTrans\/$sermonsTrans\/$recordingsTrans\/(\d+)\/[\w-\.]+\/?";
		$this->addRewriteRule($mediaPageId, $regex);
	}
	
	public function addRewriteRule($mediaPageId, $regex)
	{
		$redirect = "index.php?page_id=$mediaPageId&presentation_id=\$matches[1]";
		$priority = "top";
		
		$this->wp->call("add_rewrite_rule", $regex, $redirect, $priority);
	}
	
	public function setLocale($lang)
	{
		$requestUri = $_SERVER["REQUEST_URI"];
		
		$newLang = array_reduce($this->languages, function ($carry, $language) use ($requestUri) {
			$doesBaseRouteMatch = substr($requestUri, 0, strlen($language->baseRoute)) === $language->baseRoute;
			
			return $doesBaseRouteMatch ? $language->wpLanguageCode : $carry;
		}, $lang);
		
//		var_dump($lang, $newLang);
		
		return $newLang;
//		return "es_ES";
	}
	
	public function handleQuery()
	{
	}
}