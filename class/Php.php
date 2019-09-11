<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class Php {
	public function header($string)
	{
		header($string);
	}

	public function doEcho($string)
	{
		echo $string;
	}

	public function doDie()
	{
		die();
	}

	public function arrayRand($array, $num = 1)
    {
        if (count($array) <= $num) {
            return $array;
        }

        $keys = array_rand($array, $num);

        return array_map(function($key) use($array) {
            return $array[$key];
        }, $keys);
    }
}