<?php

namespace Avorg\Endpoint;


use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Endpoint;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Recording extends Endpoint
{
	/** @var PresentationRepository $recordingRepository */
	private $recordingRepository;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
		PresentationRepository $recordingRepository,
		WordPress $wp
	)
	{
		$this->recordingRepository = $recordingRepository;
		$this->wp = $wp;
	}

	public function getOutput()
	{
		$id = $this->wp->get_query_var( "entity_id");
		$recording = $this->recordingRepository->getPresentation($id);

		return $recording ? $recording->toJson() : null;
	}
}