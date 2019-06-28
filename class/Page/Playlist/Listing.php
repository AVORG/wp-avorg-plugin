<?php


namespace Avorg\Page\Playlist;

use Avorg\Page;
use function defined;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	protected $defaultPageTitle = "Playlists";
	protected $defaultPageContent = "Playlists";
	protected $twigTemplate = "page-playlists.twig";

	protected function getData()
	{
		// TODO: Implement getData() method.
	}

	protected function getEntityTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}