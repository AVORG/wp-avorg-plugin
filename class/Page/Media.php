<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\Recording;
use Avorg\RecordingRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
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
	
	public function throw404($query)
	{
		try {
			$this->getEntity();
		} catch (Exception $e) {
			$this->set404($query);
		}
	}

	/**
	 * @return array
	 */
	protected function getData()
	{
		return ["recording" => $this->getEntitySafe()];
	}

	/**
	 * @return Recording|null
	 */
	private function getEntitySafe()
	{
		try {
			return $this->getEntity();
		} catch (Exception $e) {
			return null;
		}
	}

	/**
	 * @return Recording|null
	 * @throws Exception
	 */
	private function getEntity()
	{
		$entityId = $this->getEntityId();

		return $this->recordingRepository->getRecording($entityId);
	}


	protected function getEntityTitle()
	{
		$recording = $this->getEntitySafe();

		return $recording ? $recording->getTitle() : null;
	}
}