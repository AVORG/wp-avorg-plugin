<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\Presentation;
use Avorg\PresentationRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;
use Exception;

if (!\defined('ABSPATH')) exit;

class Media extends Page
{
    /** @var AvorgApi $avorgApi */
    protected $avorgApi;

    /** @var PresentationRepository $presentationRepository */
    protected $presentationRepository;

    protected $defaultPageTitle = "Media Detail";
    protected $defaultPageContent = "Media Detail";
    protected $twigTemplate = "organism-recording.twig";
    protected $routeFormat = "{ language }/sermons/recordings/{ entity_id:[0-9]+ }[/{ slug }]";

    public function __construct(
    	AvorgApi $avorgApi,
		PresentationRepository $presentationRepository,
		Renderer $renderer,
		RouteFactory $routeFactory,
		WordPress $wordPress
	)
    {
        parent::__construct($renderer, $routeFactory, $wordPress);

        $this->avorgApi = $avorgApi;
        $this->presentationRepository = $presentationRepository;
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
	 * @param $title
	 * @return string
	 */
	public function filterTitle($title)
	{
		$presentation = $this->getEntitySafe();

		return $presentation ? "{$presentation->getTitle()} - AudioVerse" : $title;
	}

	/**
	 * @return array
	 */
	protected function getData()
	{
		$entity = $this->getEntitySafe();

		return ["presentation" => $entity];
	}

	/**
	 * @return Presentation|null
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
	 * @return Presentation|null
	 * @throws Exception
	 */
	private function getEntity()
	{
		$entityId = $this->getEntityId();

		return $this->presentationRepository->getPresentation($entityId);
	}


}