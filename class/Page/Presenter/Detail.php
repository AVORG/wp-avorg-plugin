<?php

namespace Avorg\Page\Presenter;

use Avorg\Page;
use Avorg\PresenterRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var PresenterRepository $presenterRepository */
	protected $presenterRepository;

	protected $defaultPageTitle = "Presenter Detail";
	protected $defaultPageContent = "Presenter Detail";
	protected $twigTemplate = "page-presenter.twig";
	protected $routeFormat = "{ language }/sermons/presenters/{ entity_id:[0-9]+ }[/{ slug }]";

	public function __construct(
		PresenterRepository $presenterRepository,
		Renderer $renderer,
		RouteFactory $routeFactory,
		WordPress $wordPress
	)
	{
		parent::__construct($renderer, $routeFactory, $wordPress);

		$this->presenterRepository = $presenterRepository;
	}

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	/**
	 * @throws Exception
	 */
	protected function getData()
	{
		$entityId = $this->getEntityId();
		$presenter = $this->presenterRepository->getPresenter($entityId);

		return [
			"presenter" => $presenter ? $presenter : null
		];
	}
}