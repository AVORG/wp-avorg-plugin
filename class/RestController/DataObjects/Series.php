<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\SeriesRepository;
use Avorg\RestController;
use Avorg\WordPress;

if (!defined('ABSPATH')) exit;

class Series extends RestController\DataObjects
{
    protected $route = '/series';

    public function __construct(SeriesRepository $repository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
    }
}