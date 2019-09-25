<?php

namespace Avorg\DataObject;


use Avorg\DataObject;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Renderer;
use Avorg\Router;
use Exception;

if (!defined('ABSPATH')) exit;

class Conference extends DataObject
{
	/** @var PresentationRepository $recordingRepository */
	private $recordingRepository;

	protected $detailClass = "Avorg\Page\Conference\Detail";

	public function __construct(
	    PresentationRepository $presentationRepository,
        Renderer $renderer,
        Router $router
    )
	{
		parent::__construct($renderer, $router);

		$this->recordingRepository = $presentationRepository;
	}

    protected function getDataArray()
    {
        return array_merge(parent::getDataArray(), [
            "secondLine" => $this->sponsorTitle
        ]);
    }

	/**
	 * @throws Exception
	 */
	public function getRecordings()
	{
		return $this->recordingRepository->getConferencePresentations($this->getId());
	}
}