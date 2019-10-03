<?php


namespace Avorg\Page\Conference;

use Avorg\DataObjectRepository\BookRepository;
use Avorg\DataObjectRepository\ConferenceRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var ConferenceRepository $conferenceRepository */
	private $conferenceRepository;

	protected $defaultPageTitle = "Conferences";
	protected $defaultPageContent = "Conferences";
	protected $twigTemplate = "page-conferences.twig";

	public function __construct(
		ConferenceRepository $conferenceRepository,
		Renderer $renderer,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->conferenceRepository = $conferenceRepository;
	}

	protected function getData()
	{
		return [
			"conferences" => $this->conferenceRepository->getConferences()
		];
	}

	protected function getTitle()
	{
		// TODO: Implement getTitle() method.
	}
}