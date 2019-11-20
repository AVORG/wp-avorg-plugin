<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\PlaylistRepository;
use Avorg\RestController;
use Avorg\Session;
use Avorg\WordPress;
use WP_REST_Request;
use function defined;

if (!defined('ABSPATH')) exit;

class UserPlaylists extends RestController\DataObjects
{
    protected $route = '/user/playlists';

    /** @var Session $session */
    private $session;

    public function __construct(PlaylistRepository $repository, Session $session, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
        $this->session = $session;
    }

    public function handleGet(WP_REST_Request $request)
    {
        $userId = $this->session->userId;
        $sessionToken = $this->session->sessionToken;
        $search = $request['search'] ?? null;
        $start = $request['start'] ?? null;

        return $this->repository->getPlaylistsByUser($userId, $sessionToken, $search, $start);
    }
}