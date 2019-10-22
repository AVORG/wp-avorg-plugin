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

        $this->wp->get_option("avorgApiKey");
		
		$this->renderer->render("admin.twig", [
		    "apiUser" => $this->wp->get_option("avorgApiUser"),
            "apiPass" => $this->wp->get_option("avorgApiPass"),
            "apiKey" => $this->wp->get_option("avorgApiKey")
        ]);
	}
	
	public function saveApiCredentials()
	{
		if (isset($_POST["save-api-credentials"])) {
			$this->wp->update_option("avorgApiUser", $_POST["api-user"]);
			$this->wp->update_option("avorgApiPass", $_POST["api-pass"]);
			$this->wp->update_option("avorgApiKey", $_POST["api-key"]);
		}
	}
}