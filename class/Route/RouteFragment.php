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

	public function getRedirectTokens() {
		if (!is_array($this->content)) return [];

		return array_reduce($this->content, function($carry, RouteFragment $child) {
			return array_merge($carry, $child->getRedirectTokens());
		}, []);
	}

	abstract public function getRegex();
}