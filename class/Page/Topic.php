<?php

namespace Avorg\Page;

use Avorg\Page;
use Avorg\PresentationRepository;
use Avorg\Renderer;
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
		PresentationRepository $presentationRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->presentationRepository = $presentationRepository;
		$this->wp = $wp;
	}

	public function throw404($query)
	{

	}

	public function setTitle($title)
	{
		return $title;
	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	protected function getTwigData()
	{
		$topicId = $this->wp->call("get_query_var", "topic_id");

		$presentations = $this->presentationRepository->getTopicPresentations($topicId);

		return [ "recordings" => $presentations ];
	}
}