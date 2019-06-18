<?php

namespace Avorg\Page\Presenter;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\PresenterRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var PresenterRepository $presenterRepository */
	private $presenterRepository;

	protected $defaultPageTitle = "Presenters";
	protected $defaultPageContent = "Presenters";
	protected $twigTemplate = "page-presenters.twig";
	protected $routeFormat = "{ language }/sermons/presenters[/{ letter }]";

	public function __construct(PresenterRepository $presenterRepository, Renderer $renderer, RouteFactory $routeFactory, WordPress $wp)
	{
		parent::__construct($renderer, $routeFactory, $wp);

		$this->presenterRepository = $presenterRepository;
	}

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	protected function getData()
	{
		$this->wp->get_query_var("letter");

		return [
			"presenters" => $this->presenterRepository->getPresenters()
		];
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}