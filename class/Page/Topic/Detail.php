<?php

namespace Avorg\Page\Topic;

use Avorg\Page;
use Avorg\RecordingRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	/** @var WordPress $wp */
	protected $wp;

	protected $defaultPageTitle = "Topic Detail";
	protected $defaultPageContent = "Topic Detail";
	protected $twigTemplate = "organism-topic.twig";

	public function __construct(
		RecordingRepository $presenterRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->recordingRepository = $presenterRepository;
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

		$recordings = $this->recordingRepository->getTopicRecordings($topicId);

		return [ "recordings" => $recordings ];
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}