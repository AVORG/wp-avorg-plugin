<?php

namespace Avorg\DataObject;


use Avorg\DataObject;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Router;
use Exception;

if (!defined('ABSPATH')) exit;

class Conference extends DataObject
{
	/** @var PresentationRepository $recordingRepository */
	private $recordingRepository;

	protected $detailClass = "Avorg\Page\Conference\Detail";

	public function __construct(PresentationRepository $recordingRepository, Router $router)
	{
		parent::__construct($router);

		$this->recordingRepository = $recordingRepository;
	}

	/**
	 * @throws Exception
	 */
	public function getRecordings()
	{
		return $this->recordingRepository->getConferencePresentations($this->getId());
	}
}