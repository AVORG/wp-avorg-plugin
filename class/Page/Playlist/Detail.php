<?php

namespace Avorg\Page\Playlist;

use Avorg\DataObject;
use Avorg\DataObject\Playlist;
use Avorg\DataObjectRepository\PlaylistRepository;
use Avorg\DataObjectRepository\PresentationRepository;
use Avorg\Page;
use Avorg\DataObject\Recording;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\ScriptFactory;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Detail extends Page
{
	/** @var PlaylistRepository $playlistRepository */
	private $playlistRepository;

	/** @var ScriptFactory $scriptFactory */
	private $scriptFactory;

	protected $defaultPageTitle = "Playlist Detail";
	protected $defaultPageContent = "Playlist Detail";
	protected $twigTemplate = "page-playlist.twig";

	public function __construct(
		PlaylistRepository $playlistRepository,
		Renderer $renderer,
		Router $router,
		ScriptFactory $scriptFactory,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $router, $wp);

		$this->playlistRepository = $playlistRepository;
		$this->scriptFactory = $scriptFactory;
	}

	protected function getData()
	{
		return [
			"recordings" => $this->getEntity()->getPresentations()
		];
	}

	protected function getTitle()
	{
		return $this->getEntity()->title;
	}

	/**
	 * @return Playlist
	 */
	private function getEntity()
	{
		return $this->playlistRepository->getPlaylist($this->getEntityId());
	}
}