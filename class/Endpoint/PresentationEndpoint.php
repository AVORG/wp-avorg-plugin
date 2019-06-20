<?php

namespace Avorg\Endpoint;


use Avorg\Endpoint;
use Avorg\PresentationRepository;
use Avorg\RouteFactory;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class PresentationEndpoint extends Endpoint
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(
		PresentationRepository $presentationRepository,
		WordPress $wp
	)
	{
		$this->presentationRepository = $presentationRepository;
		$this->wp = $wp;
	}

	public function getOutput()
	{
		$id = $this->wp->get_query_var( "entity_id");
		$presentation = $this->presentationRepository->getPresentation($id);

		return $presentation ? $presentation->toJson() : null;
	}
}