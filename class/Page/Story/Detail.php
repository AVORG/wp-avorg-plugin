<?php


namespace Avorg\Page\Story;

use Avorg\DataObject;
use Avorg\DataObjectRepository\StoryRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var StoryRepository $storyRepository */
	private $storyRepository;

	protected $defaultPageTitle = "Story";
	protected $twigTemplate = "page-story.twig";

	public function __construct(
		Renderer $renderer,
		Router $router,
		StoryRepository $storyRepository,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $router, $wp);

		return $this->storyRepository = $storyRepository;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getPageData()
	{
		return [
			"story" => $this->getEntity()
		];
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	protected function getTitle()
	{
		return $this->getEntity()->title;
	}

	/**
	 * @return DataObject
	 * @throws Exception
	 */
	protected function getEntity()
	{
		return $this->storyRepository->getStory($this->getEntityId());
	}
}