<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class MediaPage
{
	/** @var AvorgApi $avorgApi */
	private $avorgApi;
	
	/** @var Renderer $twig */
	private $twig;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(AvorgApi $avorgApi, Renderer $twig, WordPress $wordPress)
	{
		$this->avorgApi = $avorgApi;
		$this->twig = $twig;
		$this->wp = $wordPress;
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
	
	public function addMediaPageUI($content)
	{
		if ($this->isMediaPage()) {
			$presentationId = $this->wp->call("get_query_var", "presentation_id");
			$presentation = $this->avorgApi->getPresentation($presentationId);
			
			$ui = $this->twig->render("organism-recording.twig", ["presentation" => $presentation], true);
			
			return $ui . $content;
		}
		
		return $content;
	}
	
	public function isMediaPage()
	{
		$mediaPageId = intval($this->wp->call("get_option", "avorgMediaPageId"), 10);
		$thisPageId = $this->wp->call("get_the_ID");
		
		return $mediaPageId === $thisPageId;
	}
}