<?php

namespace Avorg\Route\RouteFragment;

use Avorg\Route\RouteFragment;

if (!\defined('ABSPATH')) exit;

class RouteSegment extends RouteFragment
{
	/**
	 * @return string
	 */
	public function getRegex()
	{
		return is_string($this->content) ? $this->content : $this->getChildRegex();
	}

	public function getUrlFragment()
	{
		return is_string($this->content) ? $this->content : $this->getChildUrlFragment();
	}
}