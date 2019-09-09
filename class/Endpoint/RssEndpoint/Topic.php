<?php

namespace Avorg\Endpoint\RssEndpoint;


use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\DataObjectRepository\TopicRepository;
use Avorg\Endpoint\RssEndpoint;
use Avorg\Php;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;
use Exception;
use natlib\Factory;

if (!defined('ABSPATH')) exit;

class Topic extends RssEndpoint
{
	/** @var TopicRepository $topicRepository */
	private $topicRepository;

	public function __construct(
		Factory $factory,
		Php $php,
		PresentationRepository $presentationRepository,
		Renderer $renderer,
		TopicRepository $topicRepository,
		WordPress $wp
	)
	{
		parent::__construct($factory, $php, $presentationRepository, $renderer, $wp);

		$this->topicRepository = $topicRepository;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getRecordings()
	{
		return $this->presentationRepository->getTopicPresentations($this->getEntityId());
	}

	protected function getTitle()
	{
		$topic = $this->topicRepository->getTopic($this->getEntityId());

		return "$topic->title — AudioVerse Latest Recordings";
	}

	protected function getSubtitle()
	{
		return "The latest recordings at AudioVerse";
	}
}