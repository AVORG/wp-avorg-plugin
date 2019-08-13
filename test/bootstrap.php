<?php

error_reporting(E_ALL);

define( "WP_DEBUG", "WP_DEBUG" );
define( "ABSPATH", "/" );
define( "AVORG_BASE_PATH", dirname(dirname(__FILE__)) );
define( "AVORG_BASE_URL", "AVORG_BASE_URL" );
define( "AVORG_PLUGIN_FILE", "AVORG_PLUGIN_FILE" );
define( "AVORG_TESTS_RUNNING", true );
define( "AVORG_LOGO_URL", "https://s.audioverse.org/english/gallery/sponsors/_/600/600/default-logo.png" );

include_once(AVORG_BASE_PATH . "/vendor/autoload.php");
