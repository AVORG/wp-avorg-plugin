<?php


namespace Avorg\Page\Playlist;

use Avorg\DataObjectRepository\PlaylistRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class Listing extends Page
{
	/** @var PlaylistRepository $playlistRepository */
	private $playlistRepository;

	protected $defaultPageTitle = "Playlists";
	protected $defaultPageContent = "Playlists";
	protected $twigTemplate = "page-playlists.twig";

	public function __construct(
		PlaylistRepository $playlistRepository,
		Renderer $renderer,
		Router $router,
		WordPress $wp
	)
	{
		parent::__construct($renderer, $router, $wp);

		$this->playlistRepository = $playlistRepository;
	}

	/**
	 * @throws Exception
	 */
	protected function getData()
	{
		return [
			"playlists" => $this->playlistRepository->getPlaylists()
		];
	}

	protected function getTitle()
	{
		// TODO: Implement getEntityTitle() method.
	}
}