<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class Plugin {
	/** @var AvorgApi $avorgApi */
	private $avorgApi;
	
	/** @var Twig $twig */
	private $twig;
	
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct(AvorgApi $avorgAPI, Twig $twig, WordPress $WordPress ) {
		$this->avorgApi = $avorgAPI;
		$this->twig = $twig;
		$this->wp = $WordPress;
	}
	
	public function activate() {
		$mediaPage = $this->wp->call( "get_page_by_title", "Media Detail" );
		
		if ( !$mediaPage ) {
			$this->wp->call("wp_insert_post", array(
				"post_content" => "Media Detail",
				"post_title" => "Media Detail",
				"post_status" => "publish",
				"post_type" => "page"
			), true);
		}
	}
	
	public function addMediaPageUI( $content ) {
		$pageTitle = $this->wp->call( "get_the_title" );
		
		if ( $pageTitle === "Media Detail" ) {
			$presentation = $this->avorgApi->getPresentation( $_GET["presentation_id"] );
			
			$ui = $this->twig->render( "mediaPageUI.twig", ["presentation" => $presentation], true );
			
			return $ui . $content;
		}
		
		return $content;
	}
}