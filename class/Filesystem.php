<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Filesystem
{
	public function getFile($path)
	{
		var_dump($path);
		return file_get_contents($path);
	}
}