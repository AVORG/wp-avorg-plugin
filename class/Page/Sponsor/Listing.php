<?php


namespace Avorg\Page\Sponsor;

use Avorg\DataObjectRepository\SponsorRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var SponsorRepository $sponsorRepository */
	private $sponsorRepository;

	protected $defaultPageTitle = "Sponsors";
	protected $twigTemplate = "page-sponsors.twig";

	public function __construct(
		Renderer $renderer,
		Router $router,
		SponsorRepository $seriesRepository,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $router, $wp);

		$this->sponsorRepository = $seriesRepository;
	}

	protected function getPageData()
	{
		return [
			"sponsors" => $this->sponsorRepository->getDataObjects()
		];
	}

	protected function getTitle()
	{
		// TODO: Implement getTitle() method.
	}
}