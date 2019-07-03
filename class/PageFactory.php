<?php

namespace Avorg;

use natlib\Factory;
use ReflectionException;

if (!\defined('ABSPATH')) exit;

class PageFactory
{
	/** @var Factory $factory */
	private $factory;

	/** @var Filesystem $filesystem */
	private $filesystem;

	public function __construct(Factory $factory, Filesystem $filesystem)
	{
		$this->factory = $factory;
		$this->filesystem = $filesystem;
	}

	/**
	 * @return array
	 */
	public function getPages()
	{
		$paths = $this->filesystem->getMatchingPathsRecursive("class/Page", "/\.php/") ?: [];
		$classes = array_map([$this, "pathToClassname"], $paths);

		return array_map([$this, "getPage"], $classes);
	}

	/**
	 * @param $class
	 * @return mixed
	 * @throws ReflectionException
	 */
	public function getPage($class)
	{
		return $this->factory->secure($class);
	}

	public function pathToClassname($path)
	{
		$relativePath = str_replace(AVORG_BASE_PATH . "/class", "", $path);
		$pathMinusExtension = explode(".", $relativePath)[0];
		$classname = str_replace("/", "\\", $pathMinusExtension);

		return "Avorg\\" . trim($classname, "\\");
	}
}