<?php

namespace Avorg\Endpoint;


use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Endpoint;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Presentations extends Endpoint
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	public function __construct(
		PresentationRepository $presentationRepository,
		WordPress $wp
	)
	{
		parent::__construct($wp);

		$this->presentationRepository = $presentationRepository;
	}

	public function getOutput()
	{
		$list = $this->getEntityId();

		$presentations = $this->presentationRepository->getPresentations($list);

		return $presentations ? json_encode($presentations) : '[]';
	}
}