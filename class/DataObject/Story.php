<?php

namespace Avorg\DataObject;


use Avorg\DataObject;
use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Router;
use Exception;

if (!defined('ABSPATH')) exit;

class Story extends DataObject
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	protected $detailClass = "Avorg\\Page\\Story\\Detail";

	public function __construct(RecordingRepository $recordingRepository, Router $router)
	{
		parent::__construct($router);

		$this->recordingRepository = $recordingRepository;
	}

	public function toArray()
	{
		return array_merge(parent::toArray(), [
			"recordings" => $this->getRecordingArrays()
		]);
	}

	private function getRecordingArrays()
	{
		return array_map(function(Recording $recording) {
			return $recording->toArray();
		}, $this->getRecordings());
	}

	/**
	 * @throws Exception
	 */
	public function getRecordings()
	{
		return $this->recordingRepository->getBookRecordings($this->data->id);
	}
}