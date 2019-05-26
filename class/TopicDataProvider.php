<?php

namespace Avorg;

if (!\defined('ABSPATH')) exit;

class TopicDataProvider implements iDataProvider
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
			"recordings" => $this->presentationRepository->getTopicPresentations($queryData["entity_id"])
		];
	}

	/**
	 * @param $queryData
	 * @return mixed
	 * @throws \Exception
	 */
	public function getTitle($queryData)
	{
		return null;
	}
}
