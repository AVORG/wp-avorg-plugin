<?php

namespace Avorg\Page;

use Avorg\Page;
use Avorg\PresentationRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Topic extends Page
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	/** @var WordPress $wp */
	protected $wp;

	protected $defaultPageTitle = "Topic Detail";
	protected $defaultPageContent = "Topic Detail";
	protected $twigTemplate = "organism-topic.twig";

	public function __construct(
		PresentationRepository $presenterRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->presentationRepository = $presenterRepository;
		$this->wp = $wp;
	}

	public function throw404($query)
	{

	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function getData()
	{
		$topicId = $this->wp->get_query_var( "entity_id");

		$presentations = $this->presentationRepository->getTopicPresentations($topicId);

		return [ "recordings" => $presentations ];
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}