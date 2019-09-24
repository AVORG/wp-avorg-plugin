<?php


namespace Avorg\Page\Bible;

use Avorg\DataObjectRepository\BibleRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var BibleRepository $bibleRepository */
	private $bibleRepository;

	protected $defaultPageTitle = "Bibles";
	protected $defaultPageContent = "Bibles";
	protected $twigTemplate = "page-bibles.twig";

	public function __construct(
	    BibleRepository $bibleRepository,
        Renderer $renderer,
        Router $router,
        WordPress $wp
    )
	{
		parent::__construct($renderer, $router, $wp);

		$this->bibleRepository = $bibleRepository;
	}

	protected function getPageData()
	{
		return [
			"bibles" => $this->bibleRepository->getBibles()
		];
	}

	protected function getTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}