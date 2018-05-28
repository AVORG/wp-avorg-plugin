<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class AvorgApi
{
	private $apiUser;
	private $apiPass;
	
	public function __construct()
	{
		$this->apiUser = \get_option("avorgApiUser");
		$this->apiPass = \get_option("avorgApiPass");
	}
	
	public function getPresentation($id)
	{
		if (!is_numeric($id)) return false;
		
		$url = "https://api2.audioverse.org/recordings/{$id}";
		$response = $this->getResponse($url);
		
		return json_decode($response)->result[0]->recordings;
	}
	
	public function getPresentations()
	{
		$url = "https://api2.audioverse.org/recordings";
		$response = $this->getResponse($url);

		return json_decode($response)->result;
	}
	
	private function getResponse($url)
	{
		$opts = array('http' =>
			array(
				'header' => "Content-Type: text/xml\r\n" .
					"Authorization: Basic " . base64_encode("$this->apiUser:$this->apiPass") . "\r\n"
			)
		);
		$context = stream_context_create($opts);
		
		return file_get_contents($url, false, $context);
	}
}