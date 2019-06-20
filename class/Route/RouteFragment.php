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

	public function setVariables($values)
	{
		if (!is_array($this->content)) return;

		array_walk($this->content, function(RouteFragment $child) use($values) {
			$child->setVariables($values);
		});
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

	protected function getChildUrlFragment()
	{
		return array_reduce($this->content, function ($carry, RouteFragment $child) {
			return $carry . $child->getUrlFragment();
		}, "");
	}

	public function getRewriteTags() {
		if (!is_array($this->content)) return [];

		return array_reduce($this->content, function($carry, RouteFragment $child) {
			return array_merge($carry, $child->getRewriteTags());
		}, []);
	}

	abstract public function getRegex();
	abstract public function getUrlFragment();
}