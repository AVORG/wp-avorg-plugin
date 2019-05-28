<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class Page
{
	/** @var Renderer $renderer */
	protected $renderer;

	/** @var RouteFactory $routeFactory */
	private $routeFactory;

	/** @var WordPress $wp */
	protected $wp;

	private $pageIdOptionName;
	protected $defaultPageContent;
	protected $defaultPageTitle;
	protected $twigTemplate;
	protected $routeFormat;

	/** @var iDataProvider $dataProvider */
	private $dataProvider;

	/** @var iDataProvider $dataProvider */
	private $titleProvider;


	public function __construct(Renderer $renderer, RouteFactory $routeFactory, WordPress $wp)
	{
		$this->renderer = $renderer;
		$this->routeFactory = $routeFactory;
		$this->wp = $wp;
	}

	public function throw404($query) {
		try {
			$this->getData();
		} catch (\Exception $e) {
			$this->set404($query);
		}
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	protected function getData() {
		$route = $this->getRoute();
		$tags = $route->getRewriteTags();
		$tagNames = array_keys($tags);
		$values = array_map(function($tag) {
			return $this->wp->get_query_var($tag);
		}, $tagNames);
		$queryData = array_combine($tagNames, $values);

		return $this->dataProvider->getData($queryData);
	}

	/**
	 * @param $title
	 * @return string
	 * @throws \Exception
	 */
	public function filterTitle($title)
	{
		if (! $this->titleProvider) {
			return $title;
		}

		$entityTitle = $this->titleProvider->getTitle($this->getData());

		return $entityTitle ? "$entityTitle - AudioVerse" : $title;
	}

	public function registerCallbacks()
	{
		$this->wp->add_action("wp", [$this, "registerScriptCallbacks"]);
		$this->wp->add_action("parse_query", [$this, "throw404"]);
		$this->wp->add_action("init", [$this, "createPage"]);
		$this->wp->add_filter("pre_get_document_title", [$this, "filterTitle"]);
		$this->wp->add_filter("the_title", [$this, "filterTitle"]);
		$this->wp->add_filter("the_content", [$this, "addUi"]);
		$this->wp->register_activation_hook(
			AVORG_BASE_PATH . "/wp-avorg-plugin.php",
			[$this, "createPage"]);
	}

	public function registerScriptCallbacks()
	{
		if (!$this->isThisPage()) return;

		$scripts = $this->getScripts();
		array_walk($scripts, function (Script $script) {
			$script->registerCallbacks();
		});
	}

	protected function getScripts()
	{
		return [];
	}

	public function addUi($content)
	{
		return ($this->isThisPage()) ? $this->buildUi() . $content : $content;
	}

	public function getRoute()
	{
		return $this->routeFactory->getPageRoute($this->getPostId(), $this->routeFormat);
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
		$postId = $this->getPostId();
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
			"post_content" => $this->defaultPageContent,
			"post_title" => $this->defaultPageTitle,
			"post_status" => "publish",
			"post_type" => "page"
		], true);

		$this->wp->update_option($this->pageIdOptionName, $id);
	}

	protected function isThisPage()
	{
		$postId = intval($this->getPostId(), 10);
		$thisPageId = $this->wp->get_the_ID();

		return $postId === $thisPageId;
	}

	/**
	 * @return mixed
	 */
	public function getPostId()
	{
		return $this->wp->get_option($this->pageIdOptionName);
	}

	public function setDefaultTitle($title)
	{
		$this->defaultPageTitle = $title;
	}

	public function setDefaultContent($content)
	{
		$this->defaultPageContent = $content;
	}

	public function setTwigTemplate($template)
	{
		$this->twigTemplate = $template;
	}

	public function setRouteFormat($format)
	{
		$this->routeFormat = $format;
	}

	public function setDataProvider(iDataProvider $dataProvider)
	{
		$this->dataProvider = $dataProvider;
	}

	public function setPageIdentifier($id)
	{
		$this->pageIdOptionName = $id;
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

	protected function setPageIdOptionName()
	{
		$prefix = "avorg_page_id_";
		$class = get_class($this);
		$lowercase = strtolower($class);
		$slashToUnderscore = str_replace("\\", "_", $lowercase);

		$this->pageIdOptionName = $prefix . $slashToUnderscore;
	}

	public function setTitleProvider($dataProvider)
	{
		$this->titleProvider = $dataProvider;
	}

	protected function getEntityId()
	{
		return $this->wp->get_query_var("entity_id");
	}
}