<?php


namespace Avorg\Page\Book;

use Avorg\DataObjectRepository\BookRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
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

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	protected function getPageData()
	{
		return ["book" => $this->getEntity()];
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
	 * @return mixed
	 * @throws ReflectionException
	 */
	private function getEntity()
	{
		return $this->bookRepository->getBook($this->getEntityId());
	}
}