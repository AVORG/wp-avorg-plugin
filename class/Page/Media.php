<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use Avorg\DataObject;
use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use Exception;

if (!\defined('ABSPATH')) exit;

class Media extends Page
{
    /** @var AvorgApi $avorgApi */
    protected $avorgApi;

    /** @var RecordingRepository $recordingRepository */
    protected $recordingRepository;

    protected $defaultPageTitle = "Media Detail";
    protected $defaultPageContent = "Media Detail";
    protected $twigTemplate = "organism-recording.twig";

    public function __construct(
		AvorgApi $avorgApi,
		RecordingRepository $recordingRepository,
		Renderer $renderer,
		WordPress $wordPress
	)
    {
        parent::__construct($renderer, $wordPress);

        $this->avorgApi = $avorgApi;
        $this->recordingRepository = $recordingRepository;
    }

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getData()
	{
		return ["recording" => $this->getEntity()];
	}

	/**
	 * @return string|null
	 * @throws Exception
	 */
	protected function getEntityTitle()
	{
		$recording = $this->getEntity();

		return $recording ? $recording->title : null;
	}

	/**
	 * @return DataObject|null
	 * @throws Exception
	 */
	private function getEntity()
	{
		$entityId = $this->getEntityId();

		return $this->recordingRepository->getRecording($entityId);
	}
}