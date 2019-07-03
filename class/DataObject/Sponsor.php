<?php

namespace Avorg\DataObject;


use Avorg\DataObject;
use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Router;

if (!defined('ABSPATH')) exit;

class Sponsor extends DataObject
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	protected $detailClass = "Avorg\Page\Sponsor\Detail";

	public function __construct(RecordingRepository $recordingRepository, Router $router)
	{
		parent::__construct($router);

		$this->recordingRepository = $recordingRepository;
	}

	public function getRecordings()
	{
		return $this->recordingRepository->getSponsorRecordings($this->getId());
	}
}