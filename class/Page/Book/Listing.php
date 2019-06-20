<?php


namespace Avorg\Page\Book;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	private $avorgApi;

	protected $defaultPageTitle = "Books";
	protected $defaultPageContent = "Books";
	protected $twigTemplate = "page-books.twig";

	public function __construct(AvorgApi $avorgApi, Renderer $renderer, WordPress $wp)
	{
		parent::__construct($renderer, $wp);

		$this->avorgApi = $avorgApi;
	}

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	protected function getData()
	{
		$this->avorgApi->getBooks();
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}