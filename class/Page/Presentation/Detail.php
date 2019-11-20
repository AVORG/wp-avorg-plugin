<?php

namespace Avorg\Page\Presentation;

use Avorg\AvorgApi;
use Avorg\DataObject;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use Exception;

if (!\defined('ABSPATH')) exit;

class Detail extends Page
{
    /** @var AvorgApi $avorgApi */
    protected $avorgApi;

    /** @var PresentationRepository $recordingRepository */
    protected $recordingRepository;

    protected $defaultPageTitle = "Media Detail";
    protected $twigTemplate = "page-presentation.twig";

    public function __construct(
		AvorgApi $avorgApi,
		PresentationRepository $recordingRepository,
		Renderer $renderer,
		Router $router,
		WordPress $wordPress
	)
    {
        parent::__construct($renderer, $router, $wordPress);

        $this->avorgApi = $avorgApi;
        $this->recordingRepository = $recordingRepository;
    }

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getPageData()
	{
		return ["recordings" => [$this->getEntity()]];
	}

	/**
	 * @return string|null
	 * @throws Exception
	 */
	protected function getTitle()
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

		return $this->recordingRepository->getPresentation($entityId);
	}
}