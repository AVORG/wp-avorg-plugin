<?php

namespace Avorg;

use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

abstract class Page implements iRoutable
{
	/** @var Renderer $renderer */
	protected $renderer;

	/** @var Router $router */
	protected $router;

	/** @var WordPress $wp */
	protected $wp;

	private $pageIdOptionName;
	protected $defaultPageTitle;
	protected $twigTemplate;
	protected $data;


	public function __construct(Renderer $renderer, Router $router, WordPress $wp)
	{
		$this->renderer = $renderer;
		$this->router = $router;
		$this->wp = $wp;

		$this->setPageIdOptionName();
	}

	public function throw404($query)
	{
		if (!$this->isThisPage()) return;

		try {
			$this->getData();
		} catch (Exception $e) {
			$this->set404($query);
		}
	}

	public function filterTitle($title)
	{
		if (!$this->isThisPage()) {
			return $title;
		}

		try {
			$entityTitle = $this->getTitle();

			return $entityTitle ? "$entityTitle - AudioVerse" : $title;
		} catch (Exception $e) {
			return $title;
		}
	}

	abstract protected function getTitle();

	public function registerCallbacks()
	{
		$this->wp->add_action("parse_query", [$this, "throw404"]);
		$this->wp->add_action("init", [$this, "createPage"]);
		$this->wp->add_filter("pre_get_document_title", [$this, "filterTitle"]);
		$this->wp->add_filter("the_title", [$this, "filterTitle"]);
		$this->wp->add_filter("the_content", [$this, "addUi"]);
		$this->wp->register_activation_hook(
			AVORG_BASE_PATH . "/wp-avorg-plugin.php",
			[$this, "createPage"]);
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
		return $this->renderer->render(
			$this->twigTemplate,
			$this->getData() ?: [],
			true
		);
	}

	public function createPage()
	{
		$postId = $this->getRouteId();
		$postStatus = $this->wp->get_post_status($postId);

		if ($postId === false || $postStatus === false) {
			$this->doCreatePage();
		}

		if ($postStatus === "trash") {
			$this->wp->wp_publish_post($postId);
		}
	}

	private function doCreatePage()
	{
		$id = $this->wp->wp_insert_post([
			"post_title" => $this->defaultPageTitle,
			"post_status" => "publish",
			"post_type" => "page"
		], true);

		$this->wp->update_option($this->pageIdOptionName, $id);
	}

	protected function isThisPage()
	{
		$postId = intval($this->getRouteId(), 10);
		$thisPageId = $this->wp->get_query_var("page_id");

		return $postId === $thisPageId;
	}

	/**
	 * @return mixed
	 */
	public function getRouteId()
	{
		return $this->wp->get_option($this->pageIdOptionName);
	}

	/**
	 * @param $query
	 */
	protected function set404($query)
	{
		unset($query->query_vars["page_id"]);
		$query->set_404();
		$this->wp->status_header(404);
	}

	private function setPageIdOptionName()
	{
		$prefix = "avorg_page_id_";
		$class = get_class($this);
		$lowercase = strtolower($class);
		$slashToUnderscore = str_replace("\\", "_", $lowercase);

		$this->pageIdOptionName = $prefix . $slashToUnderscore;
	}

	protected function getEntityId()
	{
		return $this->wp->get_query_var("entity_id");
	}

    private function getData() {
        return $this->data ?? $this->data = $this->getPageData();
    }

    abstract protected function getPageData();
}