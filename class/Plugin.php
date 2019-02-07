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
		$this->wp = $WordPress;

		$this->registerCallbacks();
	}

	private function registerCallbacks()
	{
		$this->wp->add_action("admin_notices", [$this, "renderAdminNotices"]);
		$this->pwa->registerCallbacks();
		$this->registerPageCallbacks();
		$this->registerAjaxActionCallbacks();
		$this->localization->registerCallbacks();
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
		$this->enqueueVideoJsAssets();
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
	
	private function enqueueVideoJsAssets()
	{
		$this->wp->wp_enqueue_style(
			"avorgVideoJsStyle",
			"//vjs.zencdn.net/7.0/video-js.min.css");
		$this->wp->wp_enqueue_script(
			"avorgVideoJsScript",
			"//vjs.zencdn.net/7.0/video.min.js");
		$this->wp->wp_enqueue_script(
			"avorgVideoJsHlsScript",
			"https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js");
	}

	private function registerPageCallbacks()
	{
		$this->registerEntityCallbacks($this->pageFactory->getPages());
	}

	private function registerAjaxActionCallbacks()
	{
		$this->registerEntityCallbacks($this->ajaxActionFactory->getActions());
	}

	private function registerEntityCallbacks($entities)
	{
		array_walk($entities, function ($entity) {
			$entity->registerCallbacks();
		});
	}
}
