<?php

namespace Avorg;

use WP_Block_Type;
use function defined;

if (!defined('ABSPATH')) exit;

/**
 * @method add_action(string $tag, callable $function_to_add, int $priority = 10, int $accepted_args = 1)
 * @method add_filter($string, array $array)
 * @method add_rewrite_rule($string, $string1, $string2)
 * @method add_rewrite_tag($string, $string1)
 * @method flush_rewrite_rules()
 * @method get_option($pageIdOptionName)
 * @method get_post_status($postId)
 * @method get_query_var($string)
 * @method get_the_ID()
 * @method plugin_dir_url($string)
 * @method register_activation_hook(string $file, callable $function)
 * @method status_header($int)
 * @method update_option($pageIdOptionName, $id)
 * @method wp_insert_post(array $postarr, bool $wp_error)
 * @method wp_publish_post($postId)
 * @method check_ajax_referer($string)
 * @method wp_enqueue_style($string, $string1)
 * @method wp_enqueue_script(string $handle, string $src = '', array $deps = array(), string|bool|null $ver = false, bool $in_footer = false)
 * @method plugins_url($string, $dirname)
 * @method settings_errors()
 * @method wp_create_nonce($id)
 * @method wp_localize_script($handle, $name, $data)
 * @method add_meta_box(array $args)
 * @method register_taxonomy($string, array $array, array $args)
 * @method get_post_meta(int $post_id, string $key = '', bool $single = false)
 * @method update_post_meta(int $post_id, string $meta_key, mixed $meta_value, mixed $prev_value = '')
 * @method add_shortcode($string, array $array)
 * @method register_post_type($string, array $args)
 * @method get_posts(array $args = null)
 * @method get_locale()
 * @method get_queried_object_id()
 * @method is_plugin_active(string $string)
 * @method wp_register_script(string $handle, string|bool $src, array $deps = array(), string|bool|null $ver = false, bool $in_footer = false)
 * @method register_block_type(string|WP_Block_Type $name, array $args = array())
 * @method register_rest_field(string|array $object_type, string $attribute, array $args = array())
 * @method register_rest_route(string $namespace, string $route, array $args = array(), bool $override = false)
 * @method admin_url(string $path = '', string $scheme = 'admin')
 * @method delete_option(string $option)
 * @method add_query_var(string $qv)
 * @method set_transient( string $transient, mixed $value, int $expiration )
 * @method get_transient( string $transient )
 */
class WordPress
{
    public function __call($function, $arguments)
    {
        $result = call_user_func_array($function, $arguments);

        if (\is_wp_error($result) && WP_DEBUG) {
            die($result->get_error_message());
        }

        return $result;
    }

    public function get_all_meta_values($key)
    {
        global $wpdb;

        $safeKey = $this->sanitize_key($key);

        $result = $wpdb->get_results(
            "SELECT $wpdb->postmeta.meta_value 
    FROM $wpdb->posts, $wpdb->postmeta
    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id AND meta_key = '$safeKey'"
        );

        return array_unique(array_map(function ($row) {
            return $row->meta_value;
        }, $result));
    }

    public function get_all_query_vars()
    {
        global $wp_query;
        return $wp_query->query_vars;
    }
}
