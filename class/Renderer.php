<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Renderer
{
	private $twig;
	private $twigGlobal;
	
	public function __construct(Twig $twig, TwigGlobal $twigGlobal)
	{
		$this->twig = $twig;
		$this->twigGlobal = $twigGlobal;
	}
	
	public function render($templateFile, $data = [], $shouldReturn = false)
	{
		try {
			$this->twigGlobal->loadData($data);
			$data = ["_GET" => $_GET, "_POST" => $_POST, "avorg" => $this->twigGlobal];
			$output = $this->twig->render($templateFile, $data);
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
}