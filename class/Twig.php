<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Twig
{
	private $twig;
	
	public function __construct()
	{
		$this->twig = $this->buildTwigEnvironment();
		
		$this->installExtensions();
	}
	
	public function render($templateFile, $data = [], $shouldReturn = false)
	{
		try {
			$template = $this->twig->load($templateFile);
			$data = ["_GET" => $_GET, "_POST" => $_POST, "avorg" => $data];
			$output = $template->render($data);
		} catch (\Exception $e) {
			$output = "Oops! Something went wrong while rendering this page.";
			if (WP_DEBUG) $output .= "<br>" . $e->getMessage();
		} finally {
			if ($shouldReturn) {
				return $output;
			} else {
				echo $output;
			}
		}
	}
	
	private function buildTwigEnvironment()
	{
		$pluginDirectory = dirname(dirname(__FILE__));
		$loader = new \Twig_Loader_Filesystem($pluginDirectory . "/view");
		
		return new \Twig_Environment($loader, array(
			"cache" => $pluginDirectory . "/cache",
			"debug" => WP_DEBUG
		));
	}
	
	private function installExtensions()
	{
		if (WP_DEBUG) {
			$this->twig->addExtension(new \Twig_Extension_Debug());
		}
	}
	
	public function registerGlobal($name, $value)
	{
		$this->twig->addGlobal($name, $value);
	}
}