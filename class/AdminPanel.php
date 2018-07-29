<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class AdminPanel
{
	/** @var Plugin $plugin */
	private $plugin;
	
	/** @var Renderer $renderer */
	private $renderer;
	
	/** $var WordPress $wp */
	private $wp;
	
	public function __construct(Plugin $plugin, Renderer $renderer, WordPress $wp)
	{
		$this->plugin = $plugin;
		$this->renderer = $renderer;
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
		$this->reactivatePlugin();
		$this->saveApiCredentials();
		
		$user = $this->wp->call("get_option", "avorgApiUser");
		$pass = $this->wp->call("get_option", "avorgApiPass");
		
		$this->renderer->render("admin.twig", ["apiUser" => $user, "apiPass" => $pass]);
	}
	
	public function reactivatePlugin()
	{
		if (isset($_POST["reactivate"])) {
			$this->plugin->activate();
		}
	}
	
	public function saveApiCredentials()
	{
		if (isset($_POST["save-api-credentials"])) {
			$this->wp->call("update_option", "avorgApiUser", $_POST["api-user"]);
			$this->wp->call("update_option", "avorgApiPass", $_POST["api-pass"]);
		}
	}
}