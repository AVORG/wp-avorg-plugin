<?php

namespace Avorg;

class WP_Query
{
	public $getCallArgs;
	public $getReturnVal;
	public $was404set = false;
	public $query_vars = [];
	
	function get(...$args)
	{
		$this->getCallArgs = $args;
		
		return $this->getReturnVal;
	}
	
	function set_404()
	{
		$this->was404set = true;
	}
}