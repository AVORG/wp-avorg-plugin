<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Renderer
{
	private $twig;
	
	public function __construct(Twig $twig)
	{
		$this->twig = $twig;
	}
	
	public function render($templateFile, $data = [], $shouldReturn = false)
	{
		try {
			$data = ["_GET" => $_GET, "_POST" => $_POST, "avorg" => $data];
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