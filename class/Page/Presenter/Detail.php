<?php

namespace Avorg\Page\Presenter;

use Avorg\DataObject;
use Avorg\DataObject\Presenter;
use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\Page;
use Avorg\Renderer;
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

	public function __construct(
		PresenterRepository $presenterRepository,
		Renderer $renderer,
		WordPress $wordPress
	)
	{
		parent::__construct($renderer, $wordPress);

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

	protected function getEntityTitle()
	{
		/** @var Presenter $presenter */
		$presenter = $this->getEntity();

		return $presenter ? $presenter->getName() : null;
	}
}