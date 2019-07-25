<?php

namespace Avorg\Endpoint;


use Avorg\DataObject\Recording\Presentation;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Endpoint;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class RelatedSermons extends Endpoint
{
	/** @var PresentationRepository $presentationRepository */
	protected $presentationRepository;

	public function __construct(
		PresentationRepository $presentationRepository,
		WordPress $wp
	)
	{
		parent::__construct($wp);

		$this->presentationRepository = $presentationRepository;
	}

	/**
	 * @return string
	 * @throws Exception
	 */
	public function getOutput()
	{
		$presentations = $this->presentationRepository->getRelatedPresentations($this->getEntityId());

		return json_encode($presentations);
	}
}