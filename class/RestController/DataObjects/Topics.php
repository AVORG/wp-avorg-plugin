<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\DataObjectRepository\SponsorRepository;
use Avorg\DataObjectRepository\StoryRepository;
use Avorg\DataObjectRepository\TopicRepository;
use Avorg\RestController;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Topics extends RestController\DataObjects
{
    protected $route = '/topics';

    public function __construct(TopicRepository $repository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
    }
}