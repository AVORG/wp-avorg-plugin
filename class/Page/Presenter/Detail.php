<?php

namespace Avorg\Page\Presenter;

use Avorg\Page;

if (!\defined('ABSPATH')) exit;

class Detail extends Page
{
	protected $defaultPageTitle = "Presenter Detail";
	protected $defaultPageContent = "Presenter Detail";
	protected $twigTemplate = "page-presenter.twig";
	protected $routeFormat = "{ language }/sermons/presenters/{ entity_id:[0-9]+ }[/{ slug }]";

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	protected function getData()
	{
		// TODO: Implement getData() method.
	}
}