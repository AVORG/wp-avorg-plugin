<?php

namespace Avorg\DataObject;


use Avorg\DataObject;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Router;
use Exception;

if (!defined('ABSPATH')) exit;

class Book extends DataObject
{
	/** @var PresentationRepository $recordingRepository */
	private $recordingRepository;

	protected $detailClass = "Avorg\Page\Book\Detail";

	public function __construct(PresentationRepository $recordingRepository, Router $router)
	{
		parent::__construct($router);

		$this->recordingRepository = $recordingRepository;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	public function toArray()
	{
		return array_merge((array) $this->data, [
			"recordings" => $this->getRecordingArrays()
		]);
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	private function getRecordingArrays()
	{
		return array_map(function (Recording $recording) {
			return $recording->toArray();
		}, $this->getRecordings());
	}

	/**
	 * @throws Exception
	 */
	public function getRecordings()
	{
		return $this->recordingRepository->getBookPresentations($this->getId());
	}
}