<?php


namespace Avorg\Page\Sponsor;

use Avorg\DataObjectRepository\SponsorRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var SponsorRepository $sponsorRepository */
	private $sponsorRepository;

	protected $defaultPageTitle = "Sponsors";
	protected $defaultPageContent = "Sponsors";
	protected $twigTemplate = "page-sponsors.twig";

	public function __construct(
		Renderer $renderer,
		SponsorRepository $sponsorRepository,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->sponsorRepository = $sponsorRepository;
	}

	protected function getData()
	{
		return [
			"sponsors" => $this->sponsorRepository->getSponsors()
		];
	}

	protected function getTitle()
	{
		// TODO: Implement getTitle() method.
	}
}