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

    protected $pageIdOptionName = "avorgMediaPageId";
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
			unset($query->query_vars["page_id"]);
			$query->set_404();
			$this->wp->call("status_header", 404);
		}
	}
	
	public function setTitle($title)
	{
		$presentationId = $this->wp->call("get_query_var", "presentation_id");
		
		$presentation = $this->presentationRepository->getPresentation($presentationId);
		
		return $presentation ? "{$presentation->getTitle()} - AudioVerse" : $title;
	}

	/**
	 * @return array
	 */
	protected function getTwigData()
	{
		$presentationId = $this->wp->call("get_query_var", "presentation_id");
		$presentation = $this->presentationRepository->getPresentation($presentationId);

		return ["presentation" => $presentation];
	}
}