<?php

namespace Avorg;

use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

abstract class Shortcode
{
	/** @var Renderer $twig */
	private $twig;

	/** @var WordPress $wp */
	private $wp;

	protected $handle;
	protected $template;

	public function __construct(
		Renderer $twig,
		WordPress $wp
	)
	{
		$this->twig = $twig;
		$this->wp = $wp;
	}

	public function init()
	{
		$this->wp->add_shortcode($this->handle, [$this, "renderShortcode"]);
	}

	/**
	 * @param $attributes
	 * @return string
	 * @throws Exception
	 */
	public function renderShortcode($attributes)
	{
		return $this->twig->render(
			$this->template, $this->getData($attributes), TRUE);
	}

	abstract protected function getData($attributes);
}