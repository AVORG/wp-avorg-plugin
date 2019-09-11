<?php

namespace Avorg;

use Avorg\Block\RelatedSermons;
use function defined;

if (!defined('ABSPATH')) exit;

class Plugin
{
	/** @var Renderer $renderer */
	private $renderer;

	/** @var WordPress $wp */
	private $wp;

	/** @var array $dependencies */
	private $dependencies;
	
	public function __construct(
		AdminPanel $adminPanel,
		AjaxActionFactory $ajaxActionFactory,
		ContentBits $contentBits,
		Localization $localization,
		PageFactory $pageFactory,
		Pwa $pwa,
		RelatedSermons $relatedSermons,
		Renderer $renderer,
		RestControllerFactory $restControllerFactory,
		Router $router,
		ScriptFactory $scriptFactory,
		WordPress $WordPress
	)
	{
		$this->renderer = $renderer;
		$this->wp = $WordPress;

		$this->dependencies = func_get_args();
	}

	public function registerCallbacks()
	{
		$this->wp->add_action("admin_notices", [$this, "renderAdminNotices"]);
		$this->wp->add_action("init", [$this, "enqueueStyles"]);

		$this->registerDependencyCallbacks();
	}

	private function registerDependencyCallbacks()
	{
		array_walk($this->dependencies, function ($dependency) {
		    if (method_exists($dependency, 'registerCallbacks')) {
                call_user_func([$dependency, 'registerCallbacks']);
            }
		});
	}
	
	public function renderAdminNotices()
	{
		$this->wp->settings_errors();

		$this->outputUnsetOptionError(
		    "permalink_structure",
            "AVORG Warning: Permalinks turned off!",
			"/wp-admin/options-permalink.php"
        );
		$this->outputUnsetOptionError(
		    "avorgApiUser",
            "AVORG Warning: Missing API username!",
			"/wp-admin/admin.php?page=avorg"
        );
		$this->outputUnsetOptionError(
		    "avorgApiPass",
            "AVORG Warning: Missing API password!",
			"/wp-admin/admin.php?page=avorg"
        );

		if (! $this->wp->is_plugin_active("pwa/pwa.php")) {
			$this->renderer->renderNotice("error",
				"AVORG Warning: PWA plugin not active!", "/wp-admin/plugins.php");
		}
	}

	/**
	 * @param $optionName
	 * @param $message
	 * @param null $url
	 */
	private function outputUnsetOptionError($optionName, $message, $url = null)
	{
		if ($this->wp->get_option($optionName)) return;

		$this->renderer->renderNotice("error", $message, $url);
	}

	public function enqueueStyles()
	{
		$this->enqueuePluginStyles();
		$this->enqueueVideoJsStyles();
	}
	
	private function enqueuePluginStyles()
	{
		$this->wp->wp_enqueue_style("avorgStyle", AVORG_BASE_URL . "/style/style.css");
		$this->wp->wp_enqueue_style("avorgEditorStyle", AVORG_BASE_URL . "/style/editor.css");
	}
	
	private function enqueueVideoJsStyles()
	{
		$this->wp->wp_enqueue_style(
			"avorgVideoJsStyle",
			"//vjs.zencdn.net/7.0/video-js.min.css");
	}
}
