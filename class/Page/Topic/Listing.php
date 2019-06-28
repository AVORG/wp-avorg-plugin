<?php


namespace Avorg\Page\Topic;

use Avorg\DataObjectRepository\TopicRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var TopicRepository $topicRepository */
	private $topicRepository;

	protected $defaultPageTitle = "Topics";
	protected $defaultPageContent = "Topics";
	protected $twigTemplate = "page-topics.twig";

	public function __construct(Renderer $renderer, TopicRepository $topicRepository, WordPress $wp)
	{
		parent::__construct($renderer, $wp);

		$this->topicRepository = $topicRepository;
	}

	protected function getData()
	{
		return [
			"topics" => $this->topicRepository->getTopics()
		];
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}