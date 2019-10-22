<?php


namespace Avorg\Page\Topic;

use Avorg\DataObjectRepository\TopicRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var TopicRepository $topicRepository */
	private $topicRepository;

	protected $defaultPageTitle = "Topics";
	protected $twigTemplate = "page-topics.twig";

	public function __construct(
	    Renderer $renderer,
        Router $router,
        TopicRepository $topicRepository,
        WordPress $wp
    )
	{
		parent::__construct($renderer, $router, $wp);

		$this->topicRepository = $topicRepository;
	}

	protected function getPageData()
	{
		return [
			"topics" => $this->topicRepository->getDataObjects()
		];
	}

	protected function getTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}