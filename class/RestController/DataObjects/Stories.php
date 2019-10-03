<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\DataObjectRepository\SponsorRepository;
use Avorg\DataObjectRepository\StoryRepository;
use Avorg\RestController;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Stories extends RestController\DataObjects
{
    protected $route = '/stories';

    public function __construct(StoryRepository $repository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
    }
}