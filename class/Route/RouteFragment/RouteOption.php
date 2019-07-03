<?php

namespace Avorg\Route\RouteFragment;

use Avorg\Route\RouteFragment;

if (!\defined('ABSPATH')) exit;

class RouteOption extends RouteFragment
{
	/**
	 * @return string
	 */
	public function getRegex()
	{
		$contentRegex = is_string($this->content) ? $this->content : $this->getChildRegex();

		return "(?:$contentRegex)?";
	}

	public function getUrlFragment()
	{
		return is_string($this->content) ? $this->content : $this->getChildUrlFragment();
	}
}