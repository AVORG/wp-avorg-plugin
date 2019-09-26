<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class AdminPanel
{
	/** @var Renderer $renderer */
	private $renderer;
	
	/** $var WordPress $wp */
	private $wp;
	
	public function __construct(Renderer $renderer, WordPress $wp)
	{
		$this->renderer = $renderer;
		$this->wp = $wp;
	}

	public function registerCallbacks()
	{
		$this->wp->add_action("admin_menu", [$this, "register"]);
	}
	
	public function register()
	{
		$this->wp->add_menu_page(
			"AVORG",
			"AVORG",
			"manage_options",
			"avorg",
			[$this, "render"]
		);
	}
	
	public function render()
	{
		$this->saveApiCredentials();
		
		$user = $this->wp->get_option("avorgApiUser");
		$pass = $this->wp->get_option("avorgApiPass");
		
		$this->renderer->render("admin.twig", ["apiUser" => $user, "apiPass" => $pass]);
	}
	
	public function saveApiCredentials()
	{
		if (isset($_POST["save-api-credentials"])) {
			$this->wp->update_option("avorgApiUser", $_POST["api-user"]);
			$this->wp->update_option("avorgApiPass", $_POST["api-pass"]);
		}
	}
}