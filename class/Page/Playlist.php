<?php

namespace Avorg\Page;

use Avorg\Page;

if (!\defined('ABSPATH')) exit;

class Playlist extends Page
{
	protected $defaultPageTitle = "Playlist Detail";
	protected $defaultPageContent = "Playlist Detail";
	protected $twigTemplate = "page-playlist.twig";
	protected $route = AVORG_ENTITY_ID_TOKEN;

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	public function setTitle($title)
	{
		return $title;
	}

	protected function getTwigData()
	{
		// TODO: Implement getTwigData() method.
	}
}