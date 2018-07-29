<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Twig
{
	private $twig;
	
	public function __construct()
	{
		$pluginDirectory = dirname(dirname(__FILE__));
		$loader = new \Twig_Loader_Filesystem($pluginDirectory . "/view");
		
		$this->twig = new \Twig_Environment($loader, array(
			"cache" => $pluginDirectory . "/cache",
			"debug" => WP_DEBUG
		));
		
		if (WP_DEBUG) {
			$this->twig->addExtension(new \Twig_Extension_Debug());
		}
	}
	
	public function render($templateFile, $data = [])
	{
		$template = $this->twig->load($templateFile);
		
		return $template->render($data);
	}
}