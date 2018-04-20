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
	
	public function render( $templateFile, $data = [], $shouldReturn = false )
	{
		try {
			$template = $this->twig->load( $templateFile );
			$data = [ "_GET" => $_GET, "_POST" => $_POST, "avorg" => $data ];
			$output = $template->render( $data );
		} catch ( \Exception $e ) {
			$output = "Oops! Something went wrong while rendering this page.";
		} finally {
			if ( $shouldReturn ) {
				return $output;
			} else {
				echo $output;
			}
		}
	}
}