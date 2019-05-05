<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class AjaxActionFactory
{
	/** @var Factory $factory */
	private $factory;

	private $actionNames = [
		"Presentation"
	];

	public function __construct(Factory $factory)
	{
		$this->factory = $factory;
	}

	public function getActions()
	{
		return array_map(function($actionName) {
			return $this->factory->secure("AjaxAction\\$actionName");
		}, $this->actionNames);
	}
}