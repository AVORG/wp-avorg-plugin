<?php

namespace Avorg\DataObject;


use Avorg\DataObject;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Renderer;
use Avorg\Router;
use Exception;

if (!defined('ABSPATH')) exit;

class Story extends DataObject
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	protected $detailClass = "Avorg\\Page\\Story\\Detail";

	public function __construct(
	    PresentationRepository $presentationRepository,
        Renderer $renderer,
        Router $router
    )
	{
		parent::__construct($renderer, $router);

		$this->presentationRepository = $presentationRepository;
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
		return $this->presentationRepository->getBookPresentations($this->data->id);
	}
}