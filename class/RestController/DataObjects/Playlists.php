<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\PlaylistRepository;
use Avorg\RestController;
use Avorg\WordPress;

if (!defined('ABSPATH')) exit;

class Playlists extends RestController\DataObjects
{
    protected $route = '/playlists';

    public function __construct(PlaylistRepository $repository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
    }
}