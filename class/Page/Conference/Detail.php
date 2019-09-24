<?php


namespace Avorg\Page\Conference;

use Avorg\DataObjectRepository\BookRepository;
use Avorg\DataObjectRepository\ConferenceRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;
use Exception;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var ConferenceRepository $conferenceRepository */
	private $conferenceRepository;

	protected $defaultPageTitle = "Conference";
	protected $defaultPageContent = "Conference";
	protected $twigTemplate = "page-conference.twig";

	public function __construct(
		ConferenceRepository $conferenceRepository,
		Renderer $renderer,
		Router $router,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $router, $wp);

		$this->conferenceRepository = $conferenceRepository;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getPageData()
	{
		return [
			"conference" => $this->getEntity()
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
	 * @return mixed
	 * @throws Exception
	 */
	protected function getEntity()
	{
		return $this->conferenceRepository->getConference($this->getEntityId());
	}
}