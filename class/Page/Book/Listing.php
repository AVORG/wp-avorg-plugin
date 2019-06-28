<?php


namespace Avorg\Page\Book;

use Avorg\DataObjectRepository\BookRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var BookRepository $bookRepository */
	private $bookRepository;

	protected $defaultPageTitle = "Books";
	protected $defaultPageContent = "Books";
	protected $twigTemplate = "page-books.twig";

	public function __construct(BookRepository $bibleRepository, Renderer $renderer, WordPress $wp)
	{
		parent::__construct($renderer, $wp);

		$this->bookRepository = $bibleRepository;
	}

	protected function getData()
	{
		return [
			"books" => $this->bookRepository->getBooks()
		];
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}