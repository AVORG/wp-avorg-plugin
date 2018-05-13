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
		$router = $this->makeRouter();
		$twig = $this->makeTwig();
		$wp = $this->makeWordPress();
		
		return new Plugin($avorgApi, $router, $twig, $wp);
	}
	
	public function makeRouter()
	{
		$wp = $this->makeWordPress();
		
		return new Router($wp);
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