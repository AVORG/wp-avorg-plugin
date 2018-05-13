<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class Plugin {
	/** @var AvorgApi $avorgApi */
	private $avorgApi;
	
	/** @var Router $router */
	private $router;
	
	/** @var Twig $twig */
	private $twig;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(AvorgApi $avorgAPI, Router $router, Twig $twig, WordPress $WordPress ) {
		$this->avorgApi = $avorgAPI;
		$this->router = $router;
		$this->twig = $twig;
		$this->wp = $WordPress;
	}
	
	public function activate() {
		$this->createMediaPage();
	}
	
	public function init() {
		$this->createMediaPage();
	}
	
	public function addMediaPageUI( $content ) {
		$pageTitle = $this->wp->call( "get_the_title" );
		
		if ( $pageTitle === "Media Detail" ) {
			$presentationId = $this->wp->call("get_query_var", "presentation_id");
			$presentation = $this->avorgApi->getPresentation( $presentationId );
			
			$ui = $this->twig->render( "mediaPageUI.twig", ["presentation" => $presentation], true );
			
			return $ui . $content;
		}
		
		return $content;
	}
	
	public function createMediaPage()
	{
		$mediaPage = $this->wp->call("get_page_by_title", "Media Detail");
		
		if (!$mediaPage) {
			$id = $this->wp->call("wp_insert_post", array(
				"post_content" => "Media Detail",
				"post_title" => "Media Detail",
				"post_status" => "publish",
				"post_type" => "page"
			), true);
			
			$this->wp->call( "update_option", "avorgMediaPageId", $id );
			$this->router->activate();
		}
	}
}