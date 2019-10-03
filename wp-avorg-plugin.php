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

use natlib\Factory;
use function defined;

if (!defined('ABSPATH')) exit;

define( "AVORG_PLUGIN_FILE", __FILE__ );
define( "AVORG_BASE_PATH", dirname(__FILE__) );
define( "AVORG_BASE_URL", \plugin_dir_url(__FILE__) );
define( "AVORG_LOGO_URL", "https://s.audioverse.org/english/gallery/sponsors/_/600/600/default-logo.png" );

include_once(AVORG_BASE_PATH . "/vendor/autoload.php");

$factory = new Factory();
$factory->secure("Avorg\\Plugin")->registerCallbacks();
