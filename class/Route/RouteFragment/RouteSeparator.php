<?php

namespace Avorg\Route\RouteFragment;

use Avorg\Route\RouteFragment;

if (!\defined('ABSPATH')) exit;

class RouteSeparator extends RouteFragment
{
	public function __construct()
	{
	}

	/**
	 * @return string
	 */
	public function getRegex()
	{
		return "\/";
	}
}