<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class AvorgApi {
	public function getPresentation( $id ) {
		if (! is_numeric( $id )) return false;
		
		$user = \get_option("avorgApiUser");
		$pass = \get_option("avorgApiPass");
		
		$opts = array('http' =>
			array(
				'header'  => "Content-Type: text/xml\r\n".
					"Authorization: Basic ".base64_encode("$user:$pass")."\r\n"
			)
		);
		
		$context  = stream_context_create($opts);
		$url = "https://api2.audioverse.org/recordings/{$id}";
		$result = file_get_contents($url, false, $context);
		return json_decode($result)->result[0]->recordings;
	}
}