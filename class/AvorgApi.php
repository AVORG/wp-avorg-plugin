<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class AvorgApi
{
	private $apiUser;
	private $apiPass;
	private $context;
	
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
	
	public function getPresentations($list = "")
	{
		$url = "https://api2.audioverse.org/recordings/$list";
		$trimmedUrl = trim($url, "/");
		$response = $this->getResponse($trimmedUrl);
		$responseObject = json_decode($response);
		
		return (isset($responseObject->result)) ? $responseObject->result : null;
	}
	
	private function getResponse($url)
	{
		if (! $this->context) $this->context = $this->createContext();

		return file_get_contents($url, false, $this->context);
	}
	
	private function createContext()
	{
		$opts = array('http' =>
			array(
				'header' => "Content-Type: text/xml\r\n" .
					"Authorization: Basic " . base64_encode("$this->apiUser:$this->apiPass") . "\r\n"
			)
		);
		
		return stream_context_create($opts);
	}
}