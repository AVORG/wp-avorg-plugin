<?php


namespace Avorg\Page\Book;

use Avorg\AvorgApi;
use Avorg\BookRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	protected $defaultPageTitle = "Book";
	protected $defaultPageContent = "Book";
	protected $twigTemplate = "page-book.twig";

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	protected function getData()
	{
		// TODO: Implement getData() method.
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}