<?php

namespace Avorg\Route\RouteFragment;

use Avorg\Route\RouteFragment;

if (!\defined('ABSPATH')) exit;

class RouteVariable extends RouteFragment
{
	/**
	 * @return string
	 */
	public function getRegex()
	{
		$contentPieces = explode(":", $this->content, 2);
		$pattern = (count($contentPieces) > 1) ? $contentPieces[1] : false;

		return $pattern ? "($pattern)" : "([\w-\.]+)";
	}

	public function getRedirectTokens()
	{
		$contentPieces = explode(":", $this->content, 2);
		$name = $contentPieces[0];

		return [$name];
	}
}