<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

/**
 * Class Factory
 * @package Avorg
 */
class Factory
{
	private $objectGraph = [
		"AdminPanel" => [
			"Plugin",
			"Renderer",
			"WordPress"
		],
		"ContentBits" => [
			"Php",
			"Renderer",
			"WordPress"
		],
		"ListShortcode" => [
			"PresentationRepository",
			"Renderer",
			"WordPress"
		],
		"Localization" => [
			"WordPress"
		],
		"Plugin" => [
			"ContentBits",
			"ListShortcode",
			"MediaPage",
			"Renderer",
			"Router",
			"WordPress"
		],
		"PresentationRepository" => [
			"AvorgApi",
			"Router"
		],
		"Renderer" => [
			"Factory",
			"Twig"
		],
		"Router" => [
			"Filesystem",
			"WordPress"
		]
	];

	/** @var AvorgApi $avorgApi */
	private $avorgApi;

	/** @var Php $php */
	private $php;

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
	 * @return Factory
	 */
	public function getFactory()
	{
		return $this;
	}

	/**
	 * @return Page\Media
	 */
	public function getMediaPage()
	{
		return $this->getObject(
			"Page\\Media",
			$this->getAvorgApi(),
			$this->getPresentationRepository(),
			$this->getRenderer(),
			$this->getWordPress()
		);
	}

	public function getTopicPage()
	{
		return $this->getObject(
			"Page\\Topic",
			$this->getRenderer(),
			$this->getWordPress()
		);
	}

	/**
	 * @return TwigGlobal
	 */
	public function getTwigGlobal()
	{
		return $this->makeObject(
			"TwigGlobal",
			$this->getLocalization(),
			$this->getWordPress()
		);
	}

	public function __call($method, $args = [])
	{
		$isGet = substr( $method, 0, 3 ) === "get";

		if (!$isGet) return null;

		$name = substr($method, 3, strlen($method) - 3);
		$dependencyNames = isset($this->objectGraph[$name]) ? $this->objectGraph[$name] : [];
		$dependencies = array_map(function($dependencyName) {
			$methodName = "get$dependencyName";
			return $this->$methodName();
		}, $dependencyNames);

		return $this->getObject($name, ...$dependencies);
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
		
		if (! isset($this->$propertyName)) $this->$propertyName = new $fullClassName(...$dependencies);
		
		return $this->$propertyName;
	}
	
	/**
	 * @param string $class
	 * @param array ...$dependencies
	 * @return mixed
	 */
	private function makeObject($class, ...$dependencies)
	{
		$fullClassName = "\\Avorg\\$class";
		$propertyName = lcfirst($class);
		$shouldUseProperty = property_exists($this, $propertyName) && isset($this->$propertyName);
		
		return $shouldUseProperty ? $this->$propertyName : new $fullClassName(...$dependencies);
	}
}