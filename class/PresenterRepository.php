<?php

namespace Avorg;

use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class PresenterRepository
{
	/** @var AvorgApi $api */
	private $api;

	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	public function __construct(AvorgApi $api, PresentationRepository $presentationRepository)
	{
		$this->api = $api;
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

		return new Presenter($rawPresenter, $this->presentationRepository);
	}

	public function getPresenters()
	{
		$rawPresenters = $this->api->getPresenters() ?: [];

		return array_map(function($rawPresenter) {
			return new Presenter($rawPresenter, $this->presentationRepository);
		}, $rawPresenters);
	}
}