<?php


namespace Avorg\Page\Series;

use Avorg\DataObjectRepository\SeriesRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var SeriesRepository $seriesRepository */
	private $seriesRepository;

	protected $defaultPageTitle = "Series";
	protected $defaultPageContent = "Series List";
	protected $twigTemplate = "page-seriesList.twig";

	public function __construct(
		Renderer $renderer,
		SeriesRepository $seriesRepository,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $wp);

		$this->seriesRepository = $seriesRepository;
	}

	/**
	 * @return array
	 * @throws Exception
	 */
	protected function getData()
	{
		return [
			"series" => $this->seriesRepository->getAllSeries()
		];
	}

	protected function getTitle()
	{
		// TODO: Implement getTitle() method.
	}
}