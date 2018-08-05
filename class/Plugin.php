<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Plugin
{
	/** @var ContentBits $contentBits */
	private $contentBits;
	
	/** @var ListShortcode $listShortcode */
	private $listShortcode;
	
	/** @var MediaPage $mediaPage */
	private $mediaPage;
	
	/** @var Router $router */
	private $router;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(
		ContentBits $contentBits,
		ListShortcode $listShortcode,
		MediaPage $mediaPage,
		Router $router,
		WordPress $WordPress
	)
	{
		$this->contentBits = $contentBits;
		$this->listShortcode = $listShortcode;
		$this->mediaPage = $mediaPage;
		$this->router = $router;
		$this->wp = $WordPress;
		
		$this->wp->call("add_action", "admin_notices", [$this, "renderAdminNotices"]);
	}
	
	public function activate()
	{
		$this->mediaPage->createMediaPage();
		$this->router->activate();
	}
	
	public function init()
	{
		$this->mediaPage->createMediaPage();
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
}