<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Plugin
{
	/** @var AjaxActionFactory $ajaxActionFactory */
	private $ajaxActionFactory;

	/** @var ContentBits $contentBits */
	private $contentBits;
	
	/** @var ListShortcode $listShortcode */
	private $listShortcode;

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

	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(
		AjaxActionFactory $ajaxActionFactory,
		ContentBits $contentBits,
		ListShortcode $listShortcode,
		Localization $localization,
		PageFactory $pageFactory,
		Pwa $pwa,
		Renderer $renderer,
		Router $router,
		ScriptFactory $scriptFactory,
		WordPress $WordPress
	)
	{
		$this->ajaxActionFactory = $ajaxActionFactory;
		$this->contentBits = $contentBits;
		$this->listShortcode = $listShortcode;
		$this->localization = $localization;
		$this->pageFactory = $pageFactory;
		$this->pwa = $pwa;
		$this->renderer = $renderer;
		$this->router = $router;
		$this->scriptFactory = $scriptFactory;
		$this->wp = $WordPress;

		$this->registerCallbacks();
	}

	private function registerCallbacks()
	{
		$this->wp->add_action("admin_notices", [$this, "renderAdminNotices"]);

		$toRegister = array_merge(
			[
				$this->pwa,
				$this->localization
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
	
	public function init()
	{
		$this->router->registerRoutes();
		$this->contentBits->init();
		$this->listShortcode->addShortcode();
	}
	
	public function enqueueScripts()
	{
		$this->enqueuePluginStyles();
		$this->enqueueVideoJsStyles();
	}
	
	public function renderAdminNotices()
	{
		$this->wp->settings_errors();

		$this->outputUnsetOptionError("permalink_structure", "AVORG Warning: Permalinks turned off!");
		$this->outputUnsetOptionError("avorgApiUser", "AVORG Warning: Missing API username!");
		$this->outputUnsetOptionError("avorgApiPass", "AVORG Warning: Missing API password!");
	}

	/**
	 * @param $optionName
	 * @param $message
	 */
	private function outputUnsetOptionError($optionName, $message)
	{
		if ($this->wp->get_option($optionName)) return;

		$this->renderer->renderNotice("error", $message);
	}
	
	private function enqueuePluginStyles()
	{
		$url = $this->wp->plugins_url("style/style.css", dirname(__FILE__));
		$this->wp->wp_enqueue_style("avorgStyle", $url);
	}
	
	private function enqueueVideoJsStyles()
	{
		$this->wp->wp_enqueue_style(
			"avorgVideoJsStyle",
			"//vjs.zencdn.net/7.0/video-js.min.css");
	}
}
