<?php

namespace Avorg\Route;

use Avorg\Filesystem;
use Avorg\LanguageFactory;
use Avorg\Route;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class EndpointRoute extends Route
{
	private $wp;

	public function __construct(Filesystem $filesystem, LanguageFactory $languageFactory, WordPress $wp)
	{
		parent::__construct($filesystem, $languageFactory);

		$this->wp = $wp;
	}

	function getBaseRoute()
	{
		$baseUrl = $this->wp->plugin_dir_url(AVORG_BASE_PATH . "/endpoint.php");
		$basePath = ltrim(parse_url($baseUrl, PHP_URL_PATH), "/");
		$str = "${basePath}endpoint.php?endpoint_id=$this->id";

		return $str;
	}

	function getBaseTags()
	{
		return [
			"endpoint_id" => "([\w-\.]+)"
		];
	}

	protected function formatQueryVar($queryKey, $matchKey)
	{
		return "$queryKey=\$$matchKey";
	}
}