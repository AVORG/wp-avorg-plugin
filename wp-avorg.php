<?php
/*
Plugin Name: AudioVerse
Description: AudioVerse plugin
Author: Nathan Arthur
Version: 1.0
Author URI: http://NathanArthur.com/
*/

namespace wp_avorg {
	if ( !\defined( 'ABSPATH' ) ) {
		exit;
	}
	
	\register_activation_hook( __FILE__, "plugin_activate" );
	\register_deactivation_hook( __FILE__, "plugin_deactivate" );
	
	function plugin_activate() {}
	function plugin_deactivate() {}
}