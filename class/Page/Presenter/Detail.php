<?php

namespace Avorg\Page\Presenter;

use Avorg\DataObject;
use Avorg\DataObject\Presenter;
use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var PresenterRepository $presenterRepository */
	protected $presenterRepository;

	protected $defaultPageTitle = "Presenter Detail";
	protected $twigTemplate = "page-presenter.twig";

	public function __construct(
		PresenterRepository $presenterRepository,
		Renderer $renderer,
		Router $router,
		WordPress $wordPress
	)
	{
		parent::__construct($renderer, $router, $wordPress);

		$this->presenterRepository = $presenterRepository;
	}

	/**
	 * @throws Exception
	 */
	protected function getPageData()
	{
		$presenter = $this->getEntity();

		return [
			"presenter" => $presenter ? $presenter : null
		];
	}

	/**
	 * @return DataObject
	 * @throws Exception
	 */
	protected function getEntity()
	{
		$entityId = $this->getEntityId();

		return $this->presenterRepository->getPresenter($entityId);
	}

	protected function getTitle()
	{
		/** @var Presenter $presenter */
		$presenter = $this->getEntity();

		return $presenter ? $presenter->getName() : null;
	}
}