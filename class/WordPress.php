<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class WordPress {
	public function call( $function, ...$arguments ) {
		$result = call_user_func_array( $function, $arguments );
		
		if ( \is_wp_error( $result ) && WP_DEBUG ) {
			die( $result->get_error_message() );
		}
		
		return $result;
	}
}