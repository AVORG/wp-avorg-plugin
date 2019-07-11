<?php

namespace Avorg;

use function defined;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

if (!defined('ABSPATH')) exit;

class Twig
{
	private $twig;
	
	public function __construct()
	{
		$loader = new FilesystemLoader(AVORG_BASE_PATH . "/view");
		
		$this->twig = new Environment($loader, array(
			"cache" => AVORG_BASE_PATH . "/cache",
			"debug" => WP_DEBUG
		));
		
		if (WP_DEBUG) {
			$this->twig->addExtension(new DebugExtension());
		}
	}
	
	public function render($templateFile, $data = [])
	{
		$template = $this->twig->load($templateFile);
		
		return $template->render($data);
	}
}