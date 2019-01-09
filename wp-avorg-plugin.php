<?php
/*
Plugin Name: WP Avorg Plugin
Description: AudioVerse plugin
Author: Nathan Arthur
Version: 1.0
Author URI: http://NathanArthur.com/
Text Domain: wp-avorg-plugin
Domain Path: /languages
*/

namespace Avorg;

if (!\defined('ABSPATH')) exit;

define( "AVORG_BASE_PATH", dirname(__FILE__) );

include_once(AVORG_BASE_PATH . "/vendor/autoload.php");

$factory = new Factory();
$plugin = $factory->get("Plugin");
$adminPanel = $factory->get("AdminPanel");
$contentBits = $factory->get("ContentBits");
$router = $factory->get("Router");

\register_activation_hook(__FILE__, array($plugin, "activate"));

\add_action("admin_menu", array($adminPanel, "register"));
\add_action("init", array($plugin, "init"));
\add_action("add_meta_boxes", array($contentBits, "addIdentifierMetaBox"));
\add_action("save_post", array($contentBits, "saveIdentifierMetaBox"));
\add_action("wp_enqueue_scripts", array($plugin, "enqueueScripts"));

\add_filter("locale", array($router, "setLocale"));
\add_filter("redirect_canonical", array($router, "filterRedirect"));



function registerServiceWorker()
{
	var_dump('hello');die;

	\wp_register_service_worker_script(
		"avorgServiceWorker",
		[
			"src" => AVORG_BASE_PATH . "/serviceWorker.js"
		]
	);

	\wp_register_service_worker_caching_route(
		'/wp-content/.*\.(?:png|gif|jpg|jpeg|svg|webp)(\?.*)?$',
		array(
			'strategy'  => \WP_Service_Worker_Caching_Routes::STRATEGY_CACHE_FIRST,
			'cacheName' => 'images',
			'plugins'   => array(
				'expiration'        => array(
					'maxEntries'    => 60,
					'maxAgeSeconds' => 60 * 60 * 24,
				),
			),
		)
	);
}


\add_action(
	"wp_front_service_worker",
	"registerServiceWorker"
);
