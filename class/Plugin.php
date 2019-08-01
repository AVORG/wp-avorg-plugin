<?php

namespace Avorg;

use Avorg\Block\RelatedSermons;
use Avorg\Shortcode\Recordings;

if (!\defined('ABSPATH')) exit;

class Plugin
{
	/** @var AdminPanel $adminPanel */
	private $adminPanel;

	/** @var AjaxActionFactory $ajaxActionFactory */
	private $ajaxActionFactory;

	/** @var BlockLoader $blockRepository */
	private $blockRepository;

	/** @var ContentBits $contentBits */
	private $contentBits;

	/** @var Localization */
	private $localization;
	
	/** @var PageFactory $pageFactory */
	private $pageFactory;

	/** @var Pwa $pwa */
	private $pwa;
	
	/** @var Renderer $renderer */
	private $renderer;
	
	/** @var Router $router */
	private $router;

	/** @var ScriptFactory $scriptFactory */
	private $scriptFactory;

	/** @var ShortcodeFactory $shortcodeFactory */
	private $shortcodeFactory;

	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(
		AdminPanel $adminPanel,
		AjaxActionFactory $ajaxActionFactory,
		BlockLoader $blockRepository,
		ContentBits $contentBits,
		Localization $localization,
		PageFactory $pageFactory,
		Pwa $pwa,
		Renderer $renderer,
		Router $router,
		ScriptFactory $scriptFactory,
		ShortcodeFactory $shortcodeFactory,
		WordPress $WordPress
	)
	{
		$this->adminPanel = $adminPanel;
		$this->ajaxActionFactory = $ajaxActionFactory;
		$this->blockRepository = $blockRepository;
		$this->contentBits = $contentBits;
		$this->localization = $localization;
		$this->pageFactory = $pageFactory;
		$this->pwa = $pwa;
		$this->renderer = $renderer;
		$this->router = $router;
		$this->scriptFactory = $scriptFactory;
		$this->shortcodeFactory = $shortcodeFactory;
		$this->wp = $WordPress;
	}

	public function registerCallbacks()
	{
		$this->wp->add_action("admin_notices", [$this, "renderAdminNotices"]);
		$this->wp->add_action("init", [$this, "init"]);
		$this->wp->register_activation_hook(AVORG_PLUGIN_FILE, [$this, "activate"]);

		$toRegister = array_merge(
			[
				$this->pwa,
				$this->localization,
				$this->contentBits,
				$this->router,
				$this->blockRepository,
				$this->adminPanel
			],
			$this->pageFactory->getPages(),
			$this->ajaxActionFactory->getActions(),
			$this->getScripts()
		);

		$this->registerEntityCallbacks($toRegister);
	}

	private function getScripts()
	{
		$paths = [
			"https://polyfill.io/v3/polyfill.min.js?features=default",
			"//vjs.zencdn.net/7.0/video.min.js",
			"https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js"
		];

		return array_map(function($path) {
			return $this->scriptFactory->getScript($path);
		}, $paths);
	}

	public function init()
	{
		$this->router->registerRoutes();
		$this->contentBits->init();

		$shortcodes = $this->shortcodeFactory->getShortcodes();
		array_walk($shortcodes, function(Shortcode $shortcode) {
			$shortcode->init();
		});

		$this->enqueueStyles();
	}

	private function registerEntityCallbacks($entities)
	{
		array_walk($entities, function ($entity) {
			$entity->registerCallbacks();
		});
	}
	
	public function activate()
	{
		$this->router->activate();
	}
	
	public function renderAdminNotices()
	{
		$this->wp->settings_errors();

		$this->outputUnsetOptionError("permalink_structure", "AVORG Warning: Permalinks turned off!",
			"/wp-admin/options-permalink.php");
		$this->outputUnsetOptionError("avorgApiUser", "AVORG Warning: Missing API username!",
			"/wp-admin/admin.php?page=avorg");
		$this->outputUnsetOptionError("avorgApiPass", "AVORG Warning: Missing API password!",
			"/wp-admin/admin.php?page=avorg");

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

	private function enqueueStyles()
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
