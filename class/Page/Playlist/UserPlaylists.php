<?php


namespace Avorg\Page\Playlist;

use Avorg\DataObjectRepository\PlaylistRepository;
use Avorg\Page;
use Avorg\Renderer;
use Avorg\Router;
use Avorg\Session;
use Avorg\WordPress;
use function defined;
use Exception;

if (!defined('ABSPATH')) exit;

class UserPlaylists extends Page
{
    protected $defaultPageTitle = "Your Playlists";
    protected $twigTemplate = "page-userPlaylists.twig";

    /** @var PlaylistRepository $playlistRepository */
    private $playlistRepository;

    /** @var Session $session */
    private $session;

    public function __construct(
        PlaylistRepository $playlistRepository,
        Renderer $renderer,
        Router $router,
        Session $session,
        WordPress $wp
    )
    {
        parent::__construct($renderer, $router, $wp);

        $this->playlistRepository = $playlistRepository;
        $this->session = $session;
    }

    protected function getTitle()
    {
        // TODO: Implement getEntityTitle() method.
    }

    protected function getPageData()
    {
        // TODO: Implement getPageData() method.
    }
}