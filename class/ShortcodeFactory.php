<?php

namespace Avorg;


use function defined;
use natlib\Factory;

if (!defined('ABSPATH')) exit;

class ShortcodeFactory
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

	public function getShortcodes()
	{
		$classes = $this->filesystem->getClassesRecursively("class/Shortcode");

		return array_map(function($class) {
			return $this->factory->secure($class);
		}, $classes);
	}
}