<?php


namespace Avorg\Page\Series;

use Avorg\DataObject;
use Avorg\DataObjectRepository\SeriesRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var SeriesRepository $seriesRepository */
	private $seriesRepository;

	protected $defaultPageTitle = "Series Detail";
	protected $defaultPageContent = "Series Detail";
	protected $twigTemplate = "page-seriesDetail.twig";

	public function __construct(
		Renderer $renderer,
		Router $router,
		SeriesRepository $seriesRepository,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $router, $wp);

		$this->seriesRepository = $seriesRepository;
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	protected function getPageData()
	{
		return [
			"series" => $this->getEntity()
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
	protected function getEntity()
	{
		return $this->seriesRepository->getOneSeries($this->getEntityId());
	}
}