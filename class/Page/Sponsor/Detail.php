<?php


namespace Avorg\Page\Sponsor;

use Avorg\DataObject;
use Avorg\DataObjectRepository\SponsorRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var SponsorRepository $sponsorRepository */
	private $sponsorRepository;

	protected $defaultPageTitle = "Sponsor";
	protected $defaultPageContent = "Sponsor";
	protected $twigTemplate = "page-sponsor.twig";

	public function __construct(
		Renderer $renderer,
		SponsorRepository $sponsorRepository,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->sponsorRepository = $sponsorRepository;
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	protected function getData()
	{
		return [
			"sponsor" => $this->getEntity()
		];
	}

	/**
	 * @return mixed
	 * @throws ReflectionException
	 */
	protected function getTitle()
	{
		return $this->getEntity()->title;
	}

	/**
	 * @return DataObject
	 * @throws ReflectionException
	 */
	private function getEntity()
	{
		return $this->sponsorRepository->getSponsor($this->getEntityId());
	}
}