<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Playlist extends Page
{
	/** @var AvorgApi $avorgApi */
	private $avorgApi;

	protected $defaultPageTitle = "Playlist Detail";
	protected $defaultPageContent = "Playlist Detail";
	protected $twigTemplate = "page-playlist.twig";
	protected $route = AVORG_BASE_ROUTE_TOKEN . "/playlists/lists/" . AVORG_ENTITY_ID_TOKEN . "/" . AVORG_VARIABLE_FRAGMENT_TOKEN;

	public function __construct(AvorgApi $avorgApi, Renderer $renderer, WordPress $wp)
	{
		parent::__construct($renderer, $wp);

		$this->avorgApi = $avorgApi;
	}

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
		$id = $this->getEntityId();
		$playlist = $this->avorgApi->getPlaylist($id);

		return [
			"playlist" => $playlist
		];
	}
}