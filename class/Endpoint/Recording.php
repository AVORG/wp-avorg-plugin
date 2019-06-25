<?php

namespace Avorg\Endpoint;


use Avorg\Endpoint;
use Avorg\RecordingRepository;
use Avorg\RouteFactory;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Recording extends Endpoint
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
		RecordingRepository $recordingRepository,
		WordPress $wp
	)
	{
		$this->recordingRepository = $recordingRepository;
		$this->wp = $wp;
	}

	public function getOutput()
	{
		$id = $this->wp->get_query_var( "entity_id");
		$recording = $this->recordingRepository->getRecording($id);

		return $recording ? $recording->toJson() : null;
	}
}