<?php

namespace Avorg\Page\Presenter;

use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var PresenterRepository $presenterRepository */
	private $presenterRepository;

	protected $defaultPageTitle = "Presenters";
	protected $defaultPageContent = "Presenters";
	protected $twigTemplate = "page-presenters.twig";

	public function __construct(PresenterRepository $presenterRepository, Renderer $renderer, WordPress $wp)
	{
		parent::__construct($renderer, $wp);

		$this->presenterRepository = $presenterRepository;
	}

	protected function getData()
	{
		$letter = $this->wp->get_query_var("letter");

		return [
			"presenters" => $this->presenterRepository->getPresenters($letter)
		];
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}