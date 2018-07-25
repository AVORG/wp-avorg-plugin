<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

/**
 * Class Factory
 * @package Avorg
 */
class Factory
{
	/** @var AdminPanel $adminPanel */
	private $adminPanel;
	
	/** @var AvorgApi $avorgApi */
	private $avorgApi;
	
	/** @var ContentBits $contentBits */
	private $contentBits;
	
	/** @var ListShortcode $listShortcode */
	private $listShortcode;
	
	/** @var Php $php */
	private $php;
	
	/** @var Plugin $plugin */
	private $plugin;
	
	/** @var Router $router */
	private $router;
	
	/** @var Twig $twig */
	private $twig;
	
	/** @var WordPress $wordPress */
	private $wordPress;
	
	/**
	 * Factory constructor.
	 * @param AvorgApi|null $avorgApi
	 * @param Php|null $php
	 * @param Twig|null $twig
	 * @param WordPress|null $wordPress
	 */
	public function __construct(
		AvorgApi $avorgApi = null,
		Php $php = null,
		Twig $twig = null,
		WordPress $wordPress = null
	)
	{
		$this->avorgApi = $avorgApi;
		$this->php = $php;
		$this->twig = $twig;
		$this->wordPress = $wordPress;
	}
	
	/**
	 * @return AdminPanel
	 */
	public function getAdminPanel()
	{
		$plugin = $this->getPlugin();
		$twig = $this->getTwig();
		$wp = $this->getWordPress();
		
		return $this->getObject("AdminPanel", $plugin, $twig, $wp);
	}
	
	/**
	 * @return Plugin
	 */
	public function getPlugin()
	{
		return $this->getObject(
			"Plugin",
			$this->getContentBits(),
			$this->getListShortcode(),
			$this->getMediaPage(),
			$this->getRouter(),
			$this->getWordPress()
		);
	}
	
	/**
	 * @return ListShortcode
	 */
	public function getListShortcode()
	{
		$api = $this->getAvorgApi();
		$twig = $this->getTwig();
		$wp = $this->getWordPress();
		
		return $this->getObject("ListShortcode", $api, $twig, $wp);
	}
	
	/**
	 * @return ContentBits
	 */
	public function getContentBits()
	{
		$php = $this->getPhp();
		$twig = $this->getTwig();
		$wp = $this->getWordPress();
		
		return $this->getObject("ContentBits", $php, $twig, $wp);
	}
	
	/**
	 * @return Router
	 */
	public function getRouter()
	{
		$wp = $this->getWordPress();
		
		return $this->getObject("Router", $wp);
	}
	
	/**
	 * @return TwigGlobal
	 */
	public function getTwigGlobal()
	{
		$twig = $this->getTwig();
		$wp = $this->getWordPress();
		
		return $this->getObject("TwigGlobal", $twig, $wp);
	}
	
	/**
	 * @return MediaPage
	 */
	public function getMediaPage()
	{
		return $this->getObject(
			"MediaPage",
			$this->getAvorgApi(),
			$this->getTwig(),
			$this->getWordPress()
		);
	}
	
	/**
	 * @return AvorgApi
	 */
	public function getAvorgApi()
	{
		return $this->getObject("AvorgApi");
	}
	
	/**
	 * @return Php
	 */
	public function getPhp()
	{
		return $this->getObject("Php");
	}
	
	/**
	 * @return Twig
	 */
	public function getTwig()
	{
		return $this->getObject("Twig");
	}
	
	/**
	 * @return WordPress
	 */
	public function getWordPress()
	{
		return $this->getObject("WordPress");
	}
	
	/**
	 * @param string $class
	 * @param array ...$dependencies
	 * @return mixed
	 */
	private function getObject($class, ...$dependencies)
	{
		$fullClassName = "\\Avorg\\$class";
		$propertyName = lcfirst($class);
		
		if (!$this->$propertyName) $this->$propertyName = new $fullClassName(...$dependencies);
		
		return $this->$propertyName;
	}
}