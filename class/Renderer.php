<?php

namespace Avorg;

use Exception;
use natlib\Factory;

if (!\defined('ABSPATH')) exit;

class Renderer
{
	private $factory;
	private $twig;
	
	public function __construct(Factory $factory, Twig $twig)
	{
		$this->factory = $factory;
		$this->twig = $twig;
	}
	
	public function renderNotice($type, $message)
	{
		$this->render("molecule-notice.twig", ["type" => $type, "message" => $message]);
	}
	
	public function render($template, $data = [], $shouldReturn = false)
	{
		$output = "Oops! Something went wrong while rendering this page.";

		try {
			$twigGlobal = $this->factory->obtain("Avorg\\TwigGlobal");
			$twigGlobal->loadData($data);
			$data = ["_GET" => $_GET, "_POST" => $_POST, "avorg" => $twigGlobal];
			$output = $this->twig->render($template, $data);

		} catch (Exception $e) {
			if (WP_DEBUG) {
				$separator = (defined("STDIN")) ? "\r\n" : "<br/>";
				$output .= $separator . $e->getMessage();
				$output .= $separator . $e->getFile() . ":" . $e->getLine();
				$output .= $separator . $e->getTraceAsString() . $separator . $separator;
				echo $output;
			};
		} finally {
			if ($shouldReturn) {
				return $output;
			} else {
				echo $output;
			}
		}
	}
}