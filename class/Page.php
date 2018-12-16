<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

abstract class Page
{
	/** @var Renderer $renderer */
	protected $renderer;

    /** @var WordPress $wp */
    protected $wp;

    protected $pageIdOptionName;
    protected $defaultPageContent;
    protected $defaultPageTitle;
    protected $twigTemplate;

    public function __construct(Renderer $renderer, WordPress $wp)
    {
    	$this->renderer = $renderer;
        $this->wp = $wp;
	}

    abstract public function throw404($query);
    abstract public function setTitle($title);
    abstract protected function getTwigData();

	public function registerCallbacks()
	{
		$this->wp->call("add_action", "parse_query", [$this, "throw404"]);
		$this->wp->call("add_action", "init", [$this, "createPage"]);
		$this->wp->call("add_filter", "pre_get_document_title", [$this, "setTitle"]);
		$this->wp->call("add_filter", "the_title", [$this, "setTitle"]);
		$this->wp->call("add_filter", "the_content", [$this, "addUi"]);
		$this->wp->call(
			"register_activation_hook",
			AVORG_BASE_PATH . "/wp-avorg-plugin.php",
			[$this, "createPage"]
		);
	}

	public function addUi($content)
	{
		return ($this->isThisPage()) ? $this->buildUi() . $content : $content;
	}

	/**
	 * @return string
	 */
	protected function buildUi()
	{
		return $this->renderer->render($this->twigTemplate, $this->getTwigData(), true);
	}

	public function createPage()
	{
		$postId = $this->getPostId();
		$postStatus = $this->wp->call("get_post_status", $postId);

		if ($postId === false || $postStatus === false) {
			$this->doCreatePage();
		}

		if ($postStatus === "trash") {
			$this->wp->call("wp_publish_post", $postId);
		}
	}

	private function doCreatePage()
	{
		$id = $this->wp->call("wp_insert_post", array(
			"post_content" => $this->defaultPageContent,
			"post_title" => $this->defaultPageTitle,
			"post_status" => "publish",
			"post_type" => "page"
		), true);

		$this->wp->call("update_option", $this->pageIdOptionName, $id);
	}

	protected function isThisPage()
	{
		$postId = intval($this->getPostId(), 10);
		$thisPageId = $this->wp->call("get_the_ID");

		return $postId === $thisPageId;
	}

	/**
	 * @return mixed
	 */
	private function getPostId()
	{
		return $this->wp->call("get_option", $this->pageIdOptionName);
	}

	/**
	 * @param $query
	 */
	protected function set404($query)
	{
		unset($query->query_vars["page_id"]);
		$query->set_404();
		$this->wp->call("status_header", 404);
	}
}