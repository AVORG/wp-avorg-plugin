<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

abstract class Page
{
    /** @var WordPress $wp */
    protected $wp;

    public function __construct(WordPress $wordPress)
    {
        $this->wp = $wordPress;
	}

    abstract function throw404($query);
    abstract function setTitle($title);
    abstract function addUi($content);

	public function registerCallbacks()
	{
		$this->wp->call("add_action", "parse_query", [$this, "throw404"]);
		$this->wp->call("add_action", "init", [$this, "createPage"]);
		$this->wp->call("add_filter", "pre_get_document_title", [$this, "setTitle"]);
		$this->wp->call("add_filter", "the_title", [$this, "setTitle"]);
		$this->wp->call("add_filter", "the_content", [$this, "addUi"]);
		$this->wp->call("register_activation_hook", AVORG_BASE_PATH . "/wp-avorg-plugin.php", [$this, "createPage"]);
	}
}