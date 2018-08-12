<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Localization
{
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(WordPress $wordPress)
	{
		$this->wp = $wordPress;
		
		$this->wp->call("add_action", "plugins_loaded", [$this, "loadLanguages"]);
	}
	
	public function i__($string)
	{
		return $this->wp->call("__", $string);
	}
	
	public function _n($single, $plural, $number)
	{
		return $this->wp->call("_n", $single, $plural, $number);
	}
	
	public function loadLanguages()
	{
		$this->wp->call(
			"load_plugin_textdomain",
			false,
			"languages"
		);
	}
}