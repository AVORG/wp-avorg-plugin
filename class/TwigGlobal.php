<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class TwigGlobal
{
	/** @var Localization $localization */
	private $localization;
	
	/** @var WordPress $wp */
	private $wp;
	
	private $data = [];
	
	public function __construct(Localization $localization, WordPress $wordPress)
	{
		$this->localization = $localization;
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
		return $this->localization->i__($string);
	}
	
	public function _n($single, $plural, $number)
	{
		return $this->localization->_n($single, $plural, $number);
	}
	
	public function loadData($data)
	{
		$this->data = array_merge($this->data, $data);
	}
}