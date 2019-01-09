<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\PresentationRepository;
use Avorg\Renderer;
use Avorg\WordPress;

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

    public function __construct(
    	AvorgApi $avorgApi,
		PresentationRepository $presentationRepository,
		Renderer $renderer,
		WordPress $wordPress
	)
    {
        parent::__construct($renderer, $wordPress);

        $this->avorgApi = $avorgApi;
        $this->presentationRepository = $presentationRepository;
    }
	
	public function throw404($query)
	{
		try {
			$this->avorgApi->getPresentation($query->get("presentation_id"));
		} catch (\Exception $e) {
			$this->set404($query);
		}
	}

	/**
	 * @param $title
	 * @return string
	 * @throws \Exception
	 */
	public function setTitle($title)
	{
		$presentation = $this->getPresentation();

		return $presentation ? "{$presentation->getTitle()} - AudioVerse" : $title;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function getTwigData()
	{
		return ["presentation" => $this->getPresentation()];
	}

	/**
	 * @return \Avorg\Presentation|null
	 * @throws \Exception
	 */
	private function getPresentation()
	{
		$presentationId = $this->wp->call("get_query_var", "presentation_id");

		return $this->presentationRepository->getPresentation($presentationId);
	}
}