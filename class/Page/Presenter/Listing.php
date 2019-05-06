<?php

namespace Avorg\Page\Presenter;

use Avorg\Page;

if (!\defined('ABSPATH')) exit;

class Listing extends Page
{
	protected $defaultPageTitle = "Presenters";
	protected $defaultPageContent = "Presenters";
	protected $routeFormat = "{ language }/sermons/presenters";

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	public function setTitle($title)
	{
		// TODO: Implement setTitle() method.
	}

	protected function getData()
	{
		// TODO: Implement getData() method.
	}
}