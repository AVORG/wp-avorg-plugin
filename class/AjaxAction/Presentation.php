<?php

namespace Avorg\AjaxAction;

use Avorg\Php;
use Avorg\PresentationRepository;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Presentation
{
	/** @var Php $php */
	private $php;

	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	/** @var WordPress $wp */
	private $wp;

	public function __construct(Php $php, PresentationRepository $presentationRepository, WordPress $wp)
	{
		$this->php = $php;
		$this->presentationRepository = $presentationRepository;
		$this->wp = $wp;
	}

	public function registerCallbacks()
	{
		$identifier = $this->getIdentifier();

		$this->wp->add_action("wp_ajax_$identifier", [$this, "run"]);
		$this->wp->add_action("wp_ajax_nopriv_$identifier", [$this, "run"]);
	}

	public function run()
	{
		$this->checkNonce();

		$id = $_POST["entity_id"];
		$presentation = $this->presentationRepository->getPresentation($id);

		echo json_encode([
			"success" => (bool) $presentation,
			"data" => $presentation ? $presentation->toJson() : null
		]);

		$this->php->doDie();
	}

	private function checkNonce()
	{
		$nonceName = $this->getIdentifier();
		$this->wp->check_ajax_referer($nonceName);
	}

	/**
	 * @return mixed
	 */
	private function getIdentifier()
	{
		return str_replace("\\", "_", get_class($this));
	}
}