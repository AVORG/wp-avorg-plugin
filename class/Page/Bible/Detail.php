<?php


namespace Avorg\Page\Bible;

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
	protected $defaultPageTitle = "Bible";
	protected $defaultPageContent = "Bible";
	protected $twigTemplate = "page-bible.twig";

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