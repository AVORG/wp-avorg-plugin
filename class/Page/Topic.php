<?php

namespace Avorg\Page;

use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Topic extends Page
{
	protected $pageIdOptionName = "avorgTopicPageId";
	protected $defaultPageTitle = "Topic Detail";
	protected $defaultPageContent = "Topic Detail";
	protected $twigTemplate = "organism-topic.twig";

	public function __construct(
		Renderer $renderer,
		WordPress $wordPress
	)
	{
		parent::__construct($renderer, $wordPress);
	}

	public function throw404($query)
	{

	}

	public function setTitle($title)
	{
		return $title;
	}

	protected function getTwigData()
	{
		return [];
	}
}