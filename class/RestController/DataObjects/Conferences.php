<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\BookRepository;
use Avorg\DataObjectRepository\ConferenceRepository;
use Avorg\RestController;
use Avorg\WordPress;
use Exception;

if (!defined('ABSPATH')) exit;

class Conferences extends RestController\DataObjects
{
    protected $route = '/conferences';

    public function __construct(ConferenceRepository $repository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
    }
}