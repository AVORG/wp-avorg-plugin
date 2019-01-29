<?php

namespace Avorg\Route;

use Avorg\Filesystem;
use Avorg\Route;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class EndpointRoute extends Route
{
	private $wp;

	private $id;

	public function __construct(Filesystem $filesystem, WordPress $wp)
	{
		parent::__construct($filesystem);

		$this->wp = $wp;
	}

	/**
	 * @param mixed $id
	 * @return EndpointRoute
	 */
	public function setEndpointId($id)
	{
		$this->id = $id;
		return $this;
	}

	function getBaseRoute()
	{
		$baseUrl = $this->wp->plugin_dir_url(AVORG_BASE_PATH . "/endpoint.php");
		$basePath = ltrim(parse_url($baseUrl, PHP_URL_PATH), "/");
		$str = "${basePath}endpoint.php?endpoint_id=$this->id";

//		var_dump($str);

		return $str;
	}

	function getBaseTags()
	{
		return [
			"endpoint_id" => "([\w-\.]+)"
		];
	}
}