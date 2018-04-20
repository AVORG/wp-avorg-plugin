<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Factory
{
	public function makeAdminPanel()
	{
		$plugin = $this->makePlugin();
		$twig = $this->makeTwig();
		$wp = $this->makeWordPress();
		
		return new AdminPanel($plugin, $twig, $wp);
	}
	
	public function makePlugin()
	{
		$avorgApi = $this->makeAvorgApi();
		$twig = $this->makeTwig();
		$wp = $this->makeWordPress();
		
		return new Plugin($avorgApi, $twig, $wp);
	}
	
	public function makeAvorgApi()
	{
		return new AvorgApi();
	}
	
	public function makeTwig()
	{
		return new Twig();
	}
	
	public function makeWordPress()
	{
		return new WordPress();
	}
}