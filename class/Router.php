<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Router
{
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(WordPress $WordPress)
	{
		$this->wp = $WordPress;
	}
	
	public function activate()
	{
		echo "Activating!";
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
}