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
$plugin = $factory->getPlugin();
$adminPanel = $factory->getAdminPanel();
$contentBits = $factory->getContentBits();
$router = $factory->getRouter();

\register_activation_hook(__FILE__, array($plugin, "activate"));

\add_action("admin_menu", array($adminPanel, "register"));
\add_action("init", array($plugin, "init"));
\add_action("add_meta_boxes", array($contentBits, "addIdentifierMetaBox"));
\add_action("save_post", array($contentBits, "saveIdentifierMetaBox"));
\add_action("wp_enqueue_scripts", array($plugin, "enqueueScripts"));

\add_filter("locale", array($router, "setLocale"));
\add_filter("redirect_canonical", array($router, "filterRedirect"));
