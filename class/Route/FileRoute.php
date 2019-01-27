<?php

namespace Avorg\Route;

use Avorg\Route;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class FileRoute extends Route
{
	private $wp;

	private $file;

	public function __construct(WordPress $wp)
	{
		$this->wp = $wp;
	}

	/**
	 * @param mixed $file
	 * @return FileRoute
	 */
	public function setFile($file)
	{
		$this->file = $file;
		return $this;
	}

	function getBaseRoute()
	{
		return $this->wp->plugin_dir_url() . $this->file;
	}
}