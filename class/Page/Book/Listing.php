<?php


namespace Avorg\Page\Book;

use Avorg\Page;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	protected $defaultPageTitle = "Books";
	protected $defaultPageContent = "Books";
	protected $twigTemplate = "page-books.twig";
	protected $routeFormat = "{ language }/audiobooks/books";

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