<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Plugin
{
	/** @var AvorgApi $avorgApi */
	private $avorgApi;
	
	/** @var ContentBits $contentBits */
	private $contentBits;
	
	/** @var Router $router */
	private $router;
	
	/** @var Twig $twig */
	private $twig;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(AvorgApi $avorgAPI, ContentBits $contentBits, Router $router, Twig $twig, WordPress $WordPress)
	{
		$this->avorgApi = $avorgAPI;
		$this->contentBits = $contentBits;
		$this->router = $router;
		$this->twig = $twig;
		$this->wp = $WordPress;
	}
	
	public function activate()
	{
		$this->createMediaPage();
		$this->router->activate();
	}
	
	public function init()
	{
		$this->createMediaPage();
		$this->contentBits->init();
	}
	
	public function addMediaPageUI($content)
	{
		if ($this->isMediaPage()) {
			$presentationId = $this->wp->call("get_query_var", "presentation_id");
			$presentation = $this->avorgApi->getPresentation($presentationId);
			
			$ui = $this->twig->render("mediaPageUI.twig", ["presentation" => $presentation], true);
			
			return $ui . $content;
		}
		
		return $content;
	}
	
	public function createMediaPage()
	{
		$mediaPageId = $this->wp->call("get_option", "avorgMediaPageId");
		$postStatus = $this->wp->call("get_post_status", $mediaPageId);
		
		if ($mediaPageId === false || $postStatus === false) {
			$id = $this->wp->call("wp_insert_post", array(
				"post_content" => "Media Detail",
				"post_title" => "Media Detail",
				"post_status" => "publish",
				"post_type" => "page"
			), true);
			
			$this->wp->call("update_option", "avorgMediaPageId", $id);
		}
		
		if ($postStatus === "trash") {
			$this->wp->call("wp_publish_post", $mediaPageId);
		}
	}
	
	public function isMediaPage()
	{
		$mediaPageId = intval($this->wp->call("get_option", "avorgMediaPageId"), 10);
		$thisPageId = $this->wp->call("get_the_ID");
		
		return $mediaPageId === $thisPageId;
	}
}