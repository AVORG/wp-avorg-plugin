<?php


namespace Avorg\Page\BibleBook;

use Avorg\DataObject;
use Avorg\DataObjectRepository\BibleBookRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;
use ReflectionException;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var BibleBookRepository $bibleBookRepository */
	private $bibleBookRepository;

	protected $defaultPageTitle = "Bible Book";
	protected $twigTemplate = "page-biblebook.twig";

	public function __construct(
		BibleBookRepository $bibleBookRepository,
		Renderer $renderer,
		Router $router,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $router, $wp);

		$this->bibleBookRepository = $bibleBookRepository;
	}

	/**
	 * @return array
	 * @throws ReflectionException
	 */
	protected function getPageData()
	{
		return [
			"book" => $this->getEntity()
		];
	}

	/**
	 * @return mixed
	 * @throws ReflectionException
	 */
	protected function getTitle()
	{
		return $this->getEntity()->name;
	}

	/**
	 * @return DataObject
	 * @throws ReflectionException
	 */
	private function getEntity()
	{
		$bibleId = $this->wp->get_query_var("bible_id");
		$drama = $this->wp->get_query_var("drama");
		$bookId = $this->wp->get_query_var("book_id");

		return $this->bibleBookRepository->getBibleBook($bibleId . $drama, $bookId);
	}
}