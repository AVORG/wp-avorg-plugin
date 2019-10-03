<?php

namespace Avorg\RestController\DataObjects;

use Avorg\DataObjectRepository\PresenterRepository;
use Avorg\RestController;
use Avorg\WordPress;
use function defined;

if (!defined('ABSPATH')) exit;

class Presenters extends RestController\DataObjects
{
    protected $route = '/presenters';

    public function __construct(PresenterRepository $repository, WordPress $wp)
    {
        parent::__construct($wp);

        $this->repository = $repository;
    }
}