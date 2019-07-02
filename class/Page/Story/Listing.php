<?php


namespace Avorg\Page\Story;

use Avorg\Page;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	protected $defaultPageTitle = "Stories";
	protected $defaultPageContent = "Stories";
	protected $twigTemplate = "page-stories.twig";

	protected function getData()
	{
		// TODO: Implement getData() method.
	}

	protected function getTitle()
	{
		// TODO: Implement getTitle() method.
	}
}