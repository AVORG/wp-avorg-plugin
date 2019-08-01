<?php

namespace Avorg;


use function defined;

if (!defined('ABSPATH')) exit;

class BlockLoader
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
		$this->wp->add_action("enqueue_block_editor_assets", [$this, "enqueueBlockEditorAssets"]);
		$this->wp->add_action("enqueue_block_assets", [$this, "enqueueBlockFrontendAssets"]);
	}

	public function enqueueBlockEditorAssets()
	{
		$this->enqueueAssets(
			'avorg_block_editor_scripts',
			'/index\.js$/'
		);
	}

	public function enqueueBlockFrontendAssets()
	{
		$this->enqueueAssets(
			'avorg_block_frontend_scripts',
			'/frontend\.js$/'
		);
	}

	/**
	 * @param $handle
	 * @param $filePattern
	 */
	private function enqueueAssets($handle, $filePattern)
	{
		$this->registerSystemJs();
		$this->registerTypeScriptLoader($handle);
		$this->localizeTypeScriptLoader($handle, $filePattern);
		$this->wp->wp_enqueue_script($handle);
	}

	private function registerSystemJs()
	{
		$this->wp->wp_register_script('system-js',
			AVORG_BASE_URL . "/node_modules/systemjs/dist/system.js");
	}

	private function registerTypeScriptLoader($handle)
	{
		$this->wp->wp_register_script(
			$handle,
			AVORG_BASE_URL . "script/ts_loader.js",
			['wp-blocks', 'wp-element', 'system-js']
		);
	}

	private function localizeTypeScriptLoader($handle, $filePattern)
	{
		$blockScriptPaths = $this->filesystem->getMatchingPathsRecursive('component/block', $filePattern);
		$blockScriptUrls = array_map(function ($path) {
			return str_replace(AVORG_BASE_PATH, AVORG_BASE_URL, $path);
		}, $blockScriptPaths);

		$this->wp->wp_localize_script($handle, 'avorg_scripts', [
			'urls' => $blockScriptUrls,
			'query' => $this->wp->get_all_query_vars()
		]);
	}
}