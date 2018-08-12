<?php

namespace Avorg;

class AvorgApi_exceptions extends \Avorg\AvorgApi
{
	public function __construct()
	{
		// override
	}
	
	public function getPresentation($id)
	{
		throw new \Exception();
	}
	
	public function getPresentations($list = "")
	{
		throw new \Exception();
	}
}