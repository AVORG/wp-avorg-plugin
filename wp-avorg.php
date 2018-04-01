<?php
/*
Plugin Name: AudioVerse
Description: AudioVerse plugin
Author: Nathan Arthur
Version: 1.0
Author URI: http://NathanArthur.com/
*/

namespace avorg;

if ( !\defined( 'ABSPATH' ) ) {
	exit;
}

include_once( dirname(__FILE__) .  "/vendor/autoload.php" );

\register_activation_hook( __FILE__, "plugin_activate" );
\register_deactivation_hook( __FILE__, "plugin_deactivate" );
\add_action( "admin_menu", "avorg\\register_admin_panel" );

function plugin_activate() {}
function plugin_deactivate() {}

function register_admin_panel() {
	\add_menu_page( "AVORG", "AVORG", "manage_options", "avorg", "avorg\\render_settings_page" );
}

function render_settings_page() {
	$twig = new Twig();
	
	echo $twig->render( "admin.twig" );
}