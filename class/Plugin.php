<?php

namespace Avorg;

use Avorg\Page\Media;

if (!\defined('ABSPATH')) exit;

class Plugin
{
	/** @var ContentBits $contentBits */
	private $contentBits;
	
	/** @var ListShortcode $listShortcode */
	private $listShortcode;
	
	/** @var Media $mediaPage */
	private $mediaPage;
	
	/** @var Renderer $renderer */
	private $renderer;
	
	/** @var Router $router */
	private $router;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(
        ContentBits $contentBits,
        ListShortcode $listShortcode,
        Media $mediaPage,
        Renderer $renderer,
        Router $router,
        WordPress $WordPress
	)
	{
		$this->contentBits = $contentBits;
		$this->listShortcode = $listShortcode;
		$this->mediaPage = $mediaPage;
		$this->renderer = $renderer;
		$this->router = $router;
		$this->wp = $WordPress;
		
		$this->wp->call("add_action", "admin_notices", [$this, "renderAdminNotices"]);
	}
	
	public function activate()
	{
		$this->mediaPage->createPage();
		$this->router->activate();
	}
	
	public function init()
	{
		$this->mediaPage->createPage();
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
		$this->wp->call("settings_errors");
		
		$this->outputPermalinkError();
		$this->outputApiUsernameError();
		$this->outputApiPasswordError();
	}
	
	private function enqueuePluginStyles()
	{
		$url = $this->wp->call("plugins_url", "style.css", dirname(__FILE__));
		$this->wp->call("wp_enqueue_style", "avorgStyle", $url);
	}
	
	private function enqueueVideoJsAssets()
	{
		$this->wp->call(
			"wp_enqueue_style",
			"avorgVideoJsStyle",
			"//vjs.zencdn.net/7.0/video-js.min.css"
		);
		$this->wp->call(
			"wp_enqueue_script",
			"avorgVideoJsScript",
			"//vjs.zencdn.net/7.0/video.min.js"
		);
		$this->wp->call(
			"wp_enqueue_script",
			"avorgVideoJsHlsScript",
			"https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.min.js"
		);
	}
	
	protected function outputPermalinkError()
	{
		if ($this->wp->call("get_option", "permalink_structure")) return;
		
		$this->renderer->renderNotice("error", "AVORG Warning: Permalinks turned off!");
	}
	
	protected function outputApiUsernameError()
	{
		if ($this->wp->call("get_option", "avorgApiUser")) return;
		
		$this->renderer->renderNotice("error", "AVORG Warning: Missing API username!");
	}
	
	protected function outputApiPasswordError()
	{
		if ($this->wp->call("get_option", "avorgApiPass")) return;
		
		$this->renderer->renderNotice("error", "AVORG Warning: Missing API password!");
	}
}