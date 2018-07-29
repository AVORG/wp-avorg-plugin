<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class TwigGlobal
{
	private $wp;
	private $data = [];
	
	public function __construct(WordPress $wordPress)
	{
		$this->wp = $wordPress;
	}
	
	public function __isset($name)
	{
		return array_key_exists($name, $this->data);
	}
	
	public function __get($name)
	{
		return $this->data[$name];
	}
	
	public function i__($string)
	{
		return $this->wp->call("__", $string);
	}
	
	public function _n($string)
	{
		return $this->wp->call("_n", $string);
	}
	
	public function loadData($data)
	{
		$this->data = array_merge($this->data, $data);
	}
}