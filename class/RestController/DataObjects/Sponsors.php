<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\DataObjectRepository\SponsorRepository;
use Avorg\RestController;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Sponsors extends RestController\DataObjects
{
    protected $route = '/sponsors';

    public function __construct(SponsorRepository $repository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
    }
}