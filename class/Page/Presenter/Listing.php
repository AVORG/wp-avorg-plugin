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
    protected function getData()
	{
		$letter = $this->wp->get_query_var("page") ?: 'A';

		return array_merge([
			"presenters" => $this->presenterRepository->getPresenters($letter),
        ], $this->getPaginationData($letter));
	}

	private function getPaginationData($currentIndex)
    {
        $keys = range("A", "Z");
        $indices = array_reduce($keys, function($carry, $key) {
            return array_merge($carry, [
                $key => $this->router->buildPath(get_class(), [
                    "page" => $key
                ])
            ]);
        }, []);

        return [
            "pagination" => [
                "indices" => $indices,
                "index" => $currentIndex
            ]
        ];
    }

	protected function getTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}