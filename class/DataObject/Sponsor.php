<?php

namespace Avorg\DataObject;


use Avorg\DataObject;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Renderer;
use Avorg\Router;

if (!defined('ABSPATH')) exit;

class Sponsor extends DataObject
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	protected $detailClass = "Avorg\Page\Sponsor\Detail";

	public function __construct(
	    PresentationRepository $presentationRepository,
        Renderer $renderer,
        Router $router
    )
	{
		parent::__construct($renderer, $router);

		$this->presentationRepository = $presentationRepository;
	}

	public function getRecordings()
	{
		return $this->presentationRepository->getSponsorPresentations($this->getId());
	}
}