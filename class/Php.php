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

	public function arrayRand(array $array, $num = 1)
    {
        if (! $array) {
            return ($num === 1) ? null : $array;
        }

        if (count($array) <= $num) {
            return ($num === 1) ? reset($array) : $array;
        }

        $rand = array_rand($array, $num);
        $keys = is_array($rand) ? $rand : [$rand];

        $items = array_map(function($key) use($array) {
            return $array[$key];
        }, $keys);

        return ($num === 1) ? $items[0] : $items;
    }

    public function initSession()
    {
        if (!session_id()) {
            session_start();
        }
    }
}