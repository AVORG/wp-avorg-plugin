<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Logger
{
	public static function log($message) {
		$isTesting = defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PHPUNIT_PHAR__');

		if ($isTesting) return;

		$line = date('Y-m-d H:i:s') . " : $message" . PHP_EOL;

		file_put_contents(AVORG_BASE_PATH . "/logs/general.log", $line, FILE_APPEND);
	}
}