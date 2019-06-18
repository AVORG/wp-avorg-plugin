<?php

namespace Avorg;

use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class PresenterRepository
{
	/** @var AvorgApi $api */
	private $api;

	/** @var LanguageFactory $languageFactory */
	private $languageFactory;

	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	public function __construct(AvorgApi $api, LanguageFactory $languageFactory, PresentationRepository $presentationRepository)
	{
		$this->api = $api;
		$this->languageFactory = $languageFactory;
		$this->presentationRepository = $presentationRepository;
	}

	/**
	 * @param $id
	 * @return Presenter|null
	 * @throws Exception
	 */
	public function getPresenter($id)
	{
		$rawPresenter = $this->api->getPresenter($id);

		if (!$rawPresenter) return null;

		return $this->buildPresenter($rawPresenter);
	}

	/**
	 * @param null $search
	 * @return array
	 * @throws Exception
	 */
	public function getPresenters($search = null)
	{
		$rawPresenters = $this->api->getPresenters($search) ?: [];

		return array_map([$this, "buildPresenter"], $rawPresenters);
	}

	/**
	 * @param $rawPresenter
	 * @return Presenter
	 */
	private function buildPresenter($rawPresenter)
	{
		return new Presenter($rawPresenter, $this->languageFactory, $this->presentationRepository);
	}
}