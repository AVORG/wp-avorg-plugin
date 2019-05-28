<?php

namespace Avorg;

use natlib\Factory;

if (!\defined('ABSPATH')) exit;

class ScriptFactory
{
	/** @var Factory $factory */
	private $factory;

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	public function getScript($path, ...$actions) {
		/** @var Script $script */
		$script = $this->factory->obtain("Avorg\\Script");

		$script->setPath($path)->setActions(...$actions);

		return $script;
	}
}