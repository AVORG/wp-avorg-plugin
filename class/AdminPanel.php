<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class AdminPanel
{
	/** @var Plugin $plugin */
	private $plugin;
	
	/** @var Twig $twig */
	private $twig;
	
	/** $var WordPress $wp */
	private $wp;
	
	public function __construct(Plugin $plugin, Twig $twig, WordPress $wp)
	{
		$this->plugin = $plugin;
		$this->twig = $twig;
		$this->wp = $wp;
	}
	
	public function register()
	{
		$this->wp->call(
			"add_menu_page",
			"AVORG",
			"AVORG",
			"manage_options",
			"avorg",
			array($this, "render")
		);
	}
	
	public function render()
	{
		if ( isset( $_POST["reactivate"] ) ) {
			$this->plugin->activate();
		}
		
		$this->twig->render("admin.twig");
	}
}