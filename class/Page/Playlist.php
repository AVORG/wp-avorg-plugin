<?php

namespace Avorg\Page;

use Avorg\AvorgApi;
use function Avorg\avorgLog;
use Avorg\Page;
use Avorg\Presentation;
use Avorg\PresentationRepository;
use Avorg\Renderer;
use Avorg\RouteFactory;
use Avorg\Script;
use Avorg\ScriptFactory;
use Avorg\WordPress;

if (!\defined('ABSPATH')) exit;

class Playlist extends Page
{
	/** @var PresentationRepository $presentationRepository */
	private $presentationRepository;

	/** @var ScriptFactory $scriptFactory */
	private $scriptFactory;

	protected $defaultPageTitle = "Playlist Detail";
	protected $defaultPageContent = "Playlist Detail";
	protected $twigTemplate = "page-playlist.twig";
	protected $routeFormat = "{ language }/playlists/lists/{ entity_id:[0-9]+ }[/{ slug }]";

	public function __construct(
		PresentationRepository $presentationRepository,
		Renderer $renderer,
		RouteFactory $routeFactory,
		ScriptFactory $scriptFactory,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $routeFactory, $wp);

		$this->presentationRepository = $presentationRepository;
		$this->scriptFactory = $scriptFactory;
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

		return [
			"recordings" => $this->presentationRepository->getPlaylistPresentations($id)
		];
	}

	protected function getScripts()
	{
		return [
			$this->scriptFactory->getScript("script/playlist.js")
		];
	}
}