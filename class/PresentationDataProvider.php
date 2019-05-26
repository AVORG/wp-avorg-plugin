<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class PresentationDataProvider implements iDataProvider
{
	/** PresentationRepository $presentationRepository */
	private $presentationRepository;

	public function __construct(PresentationRepository $presentationRepository)
	{
		$this->presentationRepository = $presentationRepository;
	}

	/**
	 * @param $queryData
	 * @return array
	 * @throws \Exception
	 */
	public function getData($queryData)
	{
		return [
			"presentation" => $this->getPresentation($queryData)
		];
	}

	/**
	 * @param $queryData
	 * @return mixed
	 * @throws \Exception
	 */
	public function getTitle($queryData)
	{
		$presentation = $this->getPresentation($queryData);

		return $presentation ? $presentation->getTitle() : null;
	}

	/**
	 * @param $queryData
	 * @return Presentation|null
	 * @throws \Exception
	 */
	private function getPresentation($queryData)
	{
		return $this->presentationRepository->getPresentation($queryData["entity_id"]);
	}
}
