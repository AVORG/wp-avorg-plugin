<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class Plugin {
	/** @var WordPress $wp */
	private $wp;
	
	public function __construct( WordPress $WordPress ) {
		$this->wp = $WordPress;
	}
	
	public function activate() {
		$this->wp->call("wp_insert_post", array(
			"ID" => 100,
			"post_content" => "Media Detail",
			"post_title" => "Media Detail",
			"post_status" => "publish",
			"post_type" => "page"
		));
	}
}