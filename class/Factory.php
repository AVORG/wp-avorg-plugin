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
	
	/** @var Renderer $renderer */
	private $renderer;
	
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
		return $this->getObject(
			"AdminPanel",
			$this->getPlugin(),
			$this->getRenderer(),
			$this->getWordPress()
		);
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
		return $this->getObject(
			"ListShortcode",
			$this->getAvorgApi(),
			$this->getRenderer(),
			$this->getWordPress()
		);
	}
	
	/**
	 * @return ContentBits
	 */
	public function getContentBits()
	{
		return $this->getObject(
			"ContentBits",
			$this->getPhp(),
			$this->getRenderer(),
			$this->getWordPress()
		);
	}
	
	/**
	 * @return Router
	 */
	public function getRouter()
	{
		return $this->getObject(
			"Router",
			$this->getWordPress()
		);
	}
	
	/**
	 * @return TwigGlobal
	 */
	public function getTwigGlobal()
	{
		return $this->getObject(
			"TwigGlobal",
			$this->getWordPress()
		);
	}
	
	/**
	 * @return MediaPage
	 */
	public function getMediaPage()
	{
		return $this->getObject(
			"MediaPage",
			$this->getAvorgApi(),
			$this->getRenderer(),
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
	 * @return Renderer
	 */
	public function getRenderer()
	{
		return $this->getObject(
			"Renderer",
			$this->getTwig()
		);
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