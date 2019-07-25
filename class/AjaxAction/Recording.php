<?php

namespace Avorg\AjaxAction;

use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Php;
use Avorg\WordPress;
use Avorg\AjaxAction;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Recording extends AjaxAction
{
	/** @var PresentationRepository $recordingRepository */
	protected $recordingRepository;

	public function __construct(Php $php, PresentationRepository $recordingRepository, WordPress $wp)
	{
		parent::__construct($php, $wp);

		$this->recordingRepository = $recordingRepository;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getResponseData()
	{
		$id = $_POST["entity_id"];
		$recording = $this->recordingRepository->getPresentation($id);

		return [
			"success" => (bool)$recording,
			"data" => json_encode($recording)
		];
	}
}