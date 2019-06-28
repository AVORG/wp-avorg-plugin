<?php


namespace Avorg\Page\Bible;

use Avorg\Page;
use function defined;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	protected $defaultPageTitle = "Bible";
	protected $defaultPageContent = "Bible";
	protected $twigTemplate = "page-bible.twig";

	protected function getData()
	{
		// TODO: Implement getData() method.
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}