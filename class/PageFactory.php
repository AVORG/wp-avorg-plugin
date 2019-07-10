<?php

namespace Avorg;

use function defined;
use natlib\Factory;
use ReflectionException;

if (!defined('ABSPATH')) exit;

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
		return array_map(
			[$this, "getPage"],
			(array) $this->filesystem->getClassesRecursively("class/Page")
		);
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
}