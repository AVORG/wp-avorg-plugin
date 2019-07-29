<?php

namespace Avorg\Block;

use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class RelatedSermons
{
	private $filename = 'block-relatedSermons.js';

	/** @var WordPress $wp */
	private $wp;

	public function __construct(WordPress $wp)
	{
		$this->wp = $wp;
	}

	public function registerCallbacks()
	{
		$this->wp->add_action("init", [$this, "init"]);
	}

	public function init()
	{
		$this->wp->wp_register_script('system-js',
			AVORG_BASE_URL . "/node_modules/systemjs/dist/system.js");

		$this->wp->wp_register_script(
			$this->getHandle(), AVORG_BASE_URL . "script/sys.js", ['wp-blocks', 'wp-element', 'system-js']);

		$this->wp->wp_localize_script($this->getHandle(), "avorg_sys", [
			"urls" => [$this->getUrl()]
		]);

		$this->wp->register_block_type($this->getName(), [
			'editor_script' => $this->getHandle()
		]);
	}

	/**
	 * @return string
	 */
	private function getHandle()
	{
		return 'avorg-' . $this->getBasename();
	}

	private function getName()
	{
		return 'avorg/' . strtolower($this->getBasename());
	}

	/**
	 * @return string
	 */
	private function getUrl()
	{
		return AVORG_BASE_URL . "script/$this->filename";
	}

	/**
	 * @return mixed
	 */
	private function getBasename()
	{
		return explode('.', $this->filename)[0];
	}
}