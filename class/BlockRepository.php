<?php

namespace Avorg;


use function defined;

if (!defined('ABSPATH')) exit;

class BlockRepository
{
	/** @var Filesystem $filesystem */
	private $filesystem;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(Filesystem $filesystem, WordPress $wp)
	{
		$this->filesystem = $filesystem;
		$this->wp = $wp;
	}

	public function registerCallbacks()
	{
		$this->wp->add_action("enqueue_block_editor_assets", [$this, "registerBlocks"]);
	}

	public function registerBlocks()
	{
		$this->registerSystemJs();
		$this->registerTypeScriptLoader();
		$this->localizeTypeScriptLoader();
		$this->wp->wp_enqueue_script('avorg_scripts');
	}

	private function registerSystemJs()
	{
		$this->wp->wp_register_script('system-js',
			AVORG_BASE_URL . "/node_modules/systemjs/dist/system.js");
	}

	private function registerTypeScriptLoader()
	{
		$this->wp->wp_register_script(
			'avorg_scripts',
			AVORG_BASE_URL . "script/ts_loader.js",
			['wp-blocks', 'wp-element', 'system-js']
		);
	}

	private function localizeTypeScriptLoader()
	{
		$blockScriptPaths = $this->filesystem->getMatchingPathsRecursive('component/block', '/index\.js/');
		$blockScriptUrls = array_map(function ($path) {
			return str_replace(AVORG_BASE_PATH, AVORG_BASE_URL, $path);
		}, $blockScriptPaths);

		$this->wp->wp_localize_script('avorg_scripts', 'avorg_scripts', [
			'urls' => $blockScriptUrls
		]);
	}
}