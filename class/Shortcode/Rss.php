<?php

namespace Avorg\Shortcode;

use Avorg\Filesystem;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\Shortcode;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Rss extends Shortcode
{
	protected $handle = "avorg-rss";
	protected $template = "shortcode-rss.twig";

	/** @var Filesystem $filesystem */
	private $filesystem;

	/** @var Router $router */
	private $router;

	public function __construct(
		Filesystem $filesystem,
		Router $router,
		Renderer $twig,
		WordPress $wp
	)
	{
		parent::__construct($twig, $wp);

		$this->filesystem = $filesystem;
		$this->router = $router;
	}

	/**
	 * @param $attributes
	 * @return array
	 * @throws Exception
	 */
	protected function getData($attributes)
	{
		return [
			"url" => $this->getRequestedUrl($attributes)
		];
	}

	/**
	 * @param $attributes
	 * @return string|null
	 * @throws Exception
	 */
	private function getRequestedUrl($attributes)
	{
		$class = $this->getClass($attributes);

		return $class ? $this->router->buildUrl($class) : null;
	}

	private function getClass($attributes)
	{
		$class = "Avorg\\Endpoint\\RssEndpoint\\" . ucfirst(strtolower($attributes["id"]));

		return class_exists($class) ? $class : null;
	}
}