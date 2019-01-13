<?php

namespace Avorg\Page;

use Avorg\Page;

if (!\defined('ABSPATH')) exit;

class Playlist extends Page
{
	protected $defaultPageTitle = "Playlist Detail";
	protected $defaultPageContent = "Playlist Detail";
	protected $twigTemplate = "page-playlist.twig";

	public function throw404($query)
	{
		// TODO: Implement throw404() method.
	}

	public function setTitle($title)
	{
		// TODO: Implement setTitle() method.
	}

	protected function getTwigData()
	{
		// TODO: Implement getTwigData() method.
	}
}