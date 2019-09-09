<?php

namespace Avorg\Route\RouteFragment;

use Avorg\Route\RouteFragment;

if (!\defined('ABSPATH')) exit;

class RouteVariable extends RouteFragment
{
	private $defaultTokenRegex = "([\w\-\.]+)";
	private $value;

	public function setVariables($values)
	{
		$this->value = array_key_exists($this->getName(), $values) ? $values[$this->getName()] : null;
	}

	/**
	 * @return string
	 */
	public function getRegex()
	{
		return $this->getPattern();
	}

	public function getUrlFragment()
	{
		return $this->value ? $this->value : "{{".$this->getName()."}}";
	}

	public function getRewriteTags()
	{
		return [$this->getName() => $this->getPattern()];
	}

	/**
	 * @return bool|mixed|string
	 */
	private function getPattern()
	{
		$pieces = $this->getContentPieces();
		$hasPattern = count($pieces) > 1;

		return $hasPattern ? "($pieces[1])" : $this->defaultTokenRegex;
	}

	private function getName()
	{
		return $this->getContentPieces()[0];
	}

	/**
	 * @return array
	 */
	private function getContentPieces()
	{
		return explode(":", $this->content, 2);
	}
}