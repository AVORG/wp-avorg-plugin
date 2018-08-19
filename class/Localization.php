<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Localization
{
	/** @var WordPress $wp */
	private $wp;
	
	private $domain = "wp-avorg-plugin";
	
	public function __construct(WordPress $wordPress)
	{
		$this->wp = $wordPress;
		
		$this->wp->call("add_action", "init", [$this, "loadLanguages"]);
	}
	
	public function i__($string)
	{
		return $this->wp->call("__", $string, $this->domain);
	}
	
	public function _n($single, $plural, $number)
	{
		return $this->wp->call("_n", $single, $plural, $number, $this->domain);
	}
	
	public function loadLanguages()
	{
		$relativePath = basename(dirname(dirname(__FILE__))) . "/languages";
		
		$this->wp->call(
			"load_plugin_textdomain",
			$this->domain,
			false,
			$relativePath
		);
	}
}