<?php

namespace Avorg\Route;

if (!\defined('ABSPATH')) exit;

abstract class RouteFragment
{
	protected $content;

	public function __construct($content)
	{
		$this->content = $content;
	}

	/**
	 * @return mixed
	 */
	protected function getChildRegex()
	{
		return array_reduce($this->content, function ($carry, RouteFragment $child) {
			return $carry . $child->getRegex();
		}, "");
	}

	abstract public function getRegex();
}