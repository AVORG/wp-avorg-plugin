<?php

define('WP_USE_THEMES', false);

$wpBasePath = dirname(dirname(dirname(__DIR__)));

require( "$wpBasePath/wp-load.php" );

echo "hello world<br>";
echo plugin_dir_url(__FILE__) . basename(__FILE__);
