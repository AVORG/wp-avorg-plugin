<?php

namespace Avorg\Page\Presenter;

use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use Exception;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var PresenterRepository $presenterRepository */
	private $presenterRepository;

	protected $defaultPageTitle = "Presenters";
	protected $defaultPageContent = "Presenters";
	protected $twigTemplate = "page-presenters.twig";

	public function __construct(
	    PresenterRepository $presenterRepository,
        Renderer $renderer,
        Router $router,
        WordPress $wp
    )
	{
		parent::__construct($renderer, $router, $wp);

		$this->presenterRepository = $presenterRepository;
	}

    /**
     * @return array
     * @throws Exception
     */
    protected function getPageData()
	{
		$letter = $this->wp->get_query_var("page") ?: 'A';

		return [
			"presenters" => $this->presenterRepository->getDataObjects($letter),
        ];
	}

    protected function getTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}