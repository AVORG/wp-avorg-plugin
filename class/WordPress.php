<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

/**
 * @method add_action($string, array $array)
 * @method add_filter($string, array $array)
 * @method add_rewrite_rule($string, $string1, $string2)
 * @method add_rewrite_tag($string, $string1)
 * @method flush_rewrite_rules()
 * @method get_option($pageIdOptionName)
 * @method get_post_status($postId)
 * @method get_query_var($string)
 * @method get_the_ID()
 * @method plugin_dir_url($string)
 * @method register_activation_hook($string, array $array)
 * @method status_header($int)
 * @method update_option($pageIdOptionName, $id)
 * @method wp_insert_post(array $array, $true)
 * @method wp_publish_post($postId)
 * @method check_ajax_referer($string)
 * @method wp_enqueue_style($string, $string1)
 * @method wp_enqueue_script($string, $string1)
 * @method plugins_url($string, $dirname)
 * @method settings_errors()
 * @method wp_create_nonce($id)
 * @method wp_localize_script($id, $string, $nonces)
 * @method add_meta_box(array $args)
 * @method register_taxonomy($string, array $array, array $args)
 * @method get_post_meta($postId, $string, $true)
 * @method update_post_meta($postId, $string, $avorgBitIdentifier)
 * @method add_shortcode($string, array $array)
 * @method register_post_type($string, array $args)
 * @method get_posts(array $param)
 */
class WordPress {
	public function __call( $function, $arguments ) {
		$result = call_user_func_array( $function, $arguments );
		
		if ( \is_wp_error( $result ) && WP_DEBUG ) {
			die( $result->get_error_message() );
		}
		
		return $result;
	}
}
