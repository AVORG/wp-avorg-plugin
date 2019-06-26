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

	public function __construct(BookRepository $bibleRepository, Renderer $renderer, WordPress $wp)
	{
		parent::__construct($renderer, $wp);

		$this->bookRepository = $bibleRepository;
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
		return ["book" => $this->getEntity()];
	}

	/**
	 * @return mixed
	 * @throws ReflectionException
	 */
	protected function getEntityTitle()
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