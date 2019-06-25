<?php

namespace Avorg\AjaxAction;

use Avorg\Php;
use Avorg\RecordingRepository;
use Avorg\WordPress;
use Avorg\AjaxAction;

if (!\defined('ABSPATH')) exit;

class Recording extends AjaxAction
{
	/** @var RecordingRepository $recordingRepository */
	protected $recordingRepository;

	public function __construct(Php $php, RecordingRepository $recordingRepository, WordPress $wp)
	{
		parent::__construct($php, $wp);

		$this->recordingRepository = $recordingRepository;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function getResponseData()
	{
		$id = $_POST["entity_id"];
		$recording = $this->recordingRepository->getRecording($id);

		return [
			"success" => (bool)$recording,
			"data" => $recording ? $recording->toJson() : null
		];
	}
}