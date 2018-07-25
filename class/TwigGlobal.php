<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class TwigGlobal
{
	private $wp;
	private $data = [];
	
	public function __construct(Twig $twig, WordPress $wordPress)
	{
		$this->wp = $wordPress;
		
		$twig->registerGlobal("avorg", $this);
	}
	
	public function __call($name, $arguments)
	{
		return $this->data[$name];
	}
	
	public function i__($string)
	{
		return $this->wp->call("__", $string);
	}
	
	public function loadData($data)
	{
		$this->data = array_merge($this->data, $data);
	}
}