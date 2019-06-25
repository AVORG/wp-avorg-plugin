<?php

namespace Avorg;

class AvorgApi_exceptions extends \Avorg\AvorgApi
{
	public function __construct()
	{
		// override
	}
	
	public function getRecording($id)
	{
		throw new \Exception();
	}
	
	public function getRecordings($list = "")
	{
		throw new \Exception();
	}
}