<?php

namespace Avorg\Endpoint;


use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Endpoint;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Recording extends Endpoint
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

	/**
	 * @return false|string|null
	 * @throws Exception
	 */
	public function getOutput()
	{
		$id = $this->wp->get_query_var( "entity_id");
		$recording = $this->presentationRepository->getPresentation($id);

		return json_encode($recording);
	}
}