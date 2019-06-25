<?php


namespace Avorg\Page\Book;

use Avorg\AvorgApi;
use Avorg\BookRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;
use function defined;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var BookRepository */
	private $bookRepository;

	protected $defaultPageTitle = "Book";
	protected $defaultPageContent = "Book";
	protected $twigTemplate = "page-book.twig";

	public function __construct(BookRepository $bookRepository, Renderer $renderer, WordPress $wp)
	{
		parent::__construct($renderer, $wp);

		$this->bookRepository = $bookRepository;
	}

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	protected function getData()
	{
		$bookId = $this->getEntityId();

		return ["book" => $this->bookRepository->getBook($bookId)];
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}