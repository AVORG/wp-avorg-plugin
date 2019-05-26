<?php

namespace Avorg\Page\Presenter;

use Avorg\Page;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Detail extends Page
{
	protected $defaultPageTitle = "Presenter Detail";
	protected $defaultPageContent = "Presenter Detail";
	protected $twigTemplate = "page-presenter.twig";
	protected $routeFormat = "{ language }/sermons/presenters/{ entity_id:[0-9]+ }[/{ slug }]";

	public function __construct(Renderer $renderer, RouteFactory $routeFactory, WordPress $wp)
	{
		parent::__construct($renderer, $routeFactory, $wp);

		$this->setPageIdOptionName();
	}

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	protected function getData()
	{
		// TODO: Implement getData() method.
	}
}