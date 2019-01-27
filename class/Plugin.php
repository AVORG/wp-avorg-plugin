<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Plugin
{
	/** @var ContentBits $contentBits */
	private $contentBits;
	
	/** @var ListShortcode $listShortcode */
	private $listShortcode;
	
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
		ContentBits $contentBits,
		ListShortcode $listShortcode,
		PageFactory $pageFactory,
		Pwa $pwa,
		Renderer $renderer,
		Router $router,
		WordPress $WordPress
	)
	{
		$this->contentBits = $contentBits;
		$this->listShortcode = $listShortcode;
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
	}
	
	public function activate()
	{
		$this->router->activate();
	}
	
	public function init()
	{
		$this->router->addRewriteRules();
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
		
		$this->outputPermalinkError();
		$this->outputApiUsernameError();
		$this->outputApiPasswordError();
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
	
	protected function outputPermalinkError()
	{
		if ($this->wp->get_option("permalink_structure")) return;
		
		$this->renderer->renderNotice("error", "AVORG Warning: Permalinks turned off!");
	}
	
	protected function outputApiUsernameError()
	{
		if ($this->wp->get_option("avorgApiUser")) return;
		
		$this->renderer->renderNotice("error", "AVORG Warning: Missing API username!");
	}
	
	protected function outputApiPasswordError()
	{
		if ($this->wp->get_option("avorgApiPass")) return;
		
		$this->renderer->renderNotice("error", "AVORG Warning: Missing API password!");
	}

	private function registerPageCallbacks()
	{
		$pages = $this->pageFactory->getPages();
		array_walk($pages, function (Page $page) {
			$page->registerCallbacks();
		});
	}
}
