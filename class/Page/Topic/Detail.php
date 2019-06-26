<?php

namespace Avorg\Page\Topic;

use Avorg\DataObjectRepository\RecordingRepository;
use Avorg\DataObjectRepository\TopicRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var RecordingRepository $recordingRepository */
	private $recordingRepository;

	/** @var TopicRepository $topicRepository */
	private $topicRepository;

	/** @var WordPress $wp */
	protected $wp;

	protected $defaultPageTitle = "Topic Detail";
	protected $defaultPageContent = "Topic Detail";
	protected $twigTemplate = "page-topic.twig";

	public function __construct(
		RecordingRepository $presenterRepository,
		Renderer $renderer,
		TopicRepository $topicRepository,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->recordingRepository = $presenterRepository;
		$this->topicRepository = $topicRepository;
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
		$topicId = $this->getEntityId();
		$topic = $this->topicRepository->getTopic($topicId);

		return $topic->title;
	}
}