<?php

namespace Avorg;

if ( !\defined( 'ABSPATH' ) ) exit;

class Twig {
	private $loader = null;
	private $twig = null;
	
	public function __construct()
	{
		$pluginDirectory = dirname( dirname( __FILE__ ) );
		
		$this->loader = new \Twig_Loader_Filesystem( $pluginDirectory . "/view" );
		$this->twig = new \Twig_Environment( $this->loader, array(
			"cache" => $pluginDirectory . "/cache",
			"debug" => WP_DEBUG
		) );
		
		if (WP_DEBUG) {
			$this->twig->addExtension(new \Twig_Extension_Debug());
		}
	}
	
	public function render( $templateFile )
	{
		$template = $this->twig->load( $templateFile );
		
		echo $template->render( array(
			"_POST" => $_POST
		) );
	}
}