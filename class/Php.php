<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class Php {
	public function array_rand( ...$arguments ) {
		return array_rand( ...$arguments );
	}

	public function header($string)
	{
		header($string);
	}

	public function doDie()
	{
		die();
	}
}