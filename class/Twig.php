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
			"auto_reload" => WP_DEBUG
		) );
	}
	
	public function render( $templateFile )
	{
		$template = $this->twig->load( $templateFile );
		
		return $template->render( array() );
	}
}