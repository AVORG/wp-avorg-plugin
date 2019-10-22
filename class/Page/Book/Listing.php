<?php


namespace Avorg\Page\Book;

use Avorg\DataObjectRepository\BookRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var BookRepository $bookRepository */
	private $bookRepository;

	protected $defaultPageTitle = "Books";
	protected $twigTemplate = "page-books.twig";

	public function __construct(
	    BookRepository $bibleRepository,
        Renderer $renderer,
        Router $router,
        WordPress $wp
    )
	{
		parent::__construct($renderer, $router, $wp);

		$this->bookRepository = $bibleRepository;
	}

	protected function getPageData()
	{
		return [
			"books" => $this->bookRepository->getDataObjects()
		];
	}

	protected function getTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}