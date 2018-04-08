<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class WordPress {
	public function call( $function, ...$arguments ) {
		call_user_func_array( $function, $arguments );
	}
}