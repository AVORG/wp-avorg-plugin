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
		$this->addRewriteRule();
		$this->wp->call("flush_rewrite_rules");
	}
	
	public function addRewriteRule()
	{
		$mediaPageId = $this->wp->call( "get_option", "avorgMediaPageId" );
		
		$match = "^english\/sermons\/recordings\/(\d+)\/[\w-\.]+\/?";
		$redirect = "index.php?page_id=$mediaPageId&presentation_id=\$matches[1]";
		$priority = "top";
		
		$this->wp->call("add_rewrite_tag", "%presentation_id%", "(\d+)");
		$this->wp->call("add_rewrite_rule", $match, $redirect, $priority);
	}
}