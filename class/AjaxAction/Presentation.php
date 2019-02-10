<?php

namespace Avorg\AjaxAction;

use Avorg\Php;
use Avorg\PresentationRepository;
use Avorg\WordPress;
use Avorg\AjaxAction;

if (!\defined('ABSPATH')) exit;

class Presentation extends AjaxAction
{
	/** @var PresentationRepository $presentationRepository */
	protected $presentationRepository;

	public function __construct(Php $php, PresentationRepository $presentationRepository, WordPress $wp)
	{
		parent::__construct($php, $wp);

		$this->presentationRepository = $presentationRepository;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function getResponseData()
	{
		$id = $_POST["entity_id"];
		$presentation = $this->presentationRepository->getPresentation($id);

		return [
			"success" => (bool)$presentation,
			"data" => $presentation ? $presentation->toJson() : null
		];
	}
}