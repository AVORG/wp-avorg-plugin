<?php

namespace Avorg;


use function defined;

if (!defined('ABSPATH')) exit;

abstract class Endpoint implements iRoutable
{
	protected $routeFormat;

	abstract public function getOutput();

	/**
	 * @return string
	 */
	public function getRouteId()
	{
		return str_replace("\\", "_", get_class($this));
	}
}